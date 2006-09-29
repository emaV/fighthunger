<?php

class FlickrLib
{
    var $apiURL;
    var $apiKey;
    var $userID;
    
    var $cacheTime;
    var $cacheDir;
    
    var $parseOutput;
    var $parseBookmark;
    
    var $photos;
    var $errNo;
    var $errMsg;

    function FlickrLib ($apiKey, $userID, $cacheTime = 300)
    {
        $this->apiURL = 'http://www.flickr.com/services/rest';
        $this->apiKey = $apiKey;
        $this->userID = $userID;
        
        $this->cacheTime = $cacheTime;
        $this->cacheDir = '/var/tmp/FlickrLibCache';
        
        $this->parseOutput   = array();
        $this->parseBookmark = '';
        
        $this->errNo  = 0;
        $this->errMsg = '';

        @mkdir($this->cacheDir);
        @mkdir($this->cacheDir . '/rest');
        @mkdir($this->cacheDir . '/photos');
    }
    
    function _randomOneIn ($upperLimit)
    {
        if (rand(1, $upperLimit) == 1)
            return(true);
        else
            return(false);
    }
    
    function _fetchREST ($restURL)
    {
        $cachePath = $this->cacheDir . '/rest/' . md5($restURL);
        
        if (file_exists($cachePath))
        {
            $result = file_get_contents($cachePath);
            
            // most of the time, just return cache
            if ($this->_randomOneIn(10) == false)
                return($result);
        }
        
        //
        // if we got this far, we should be trying to update the cache
        // (based on probability above or no cache yet so most people get speedy returns)
        //

        if (file_exists($cachePath) == false || time() - filemtime($cachePath) > $this->cacheTime)
        {
            // old cache or no cache, try fetch
            $ch = curl_init();
        
            curl_setopt($ch, CURLOPT_URL, $restURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
            $result = curl_exec($ch);
        
            // if bad fetch, just bail and return cache if possible
            if (curl_errno($ch) || strlen($result) == 0)
            {
                if (file_exists($cachePath))
                    return(file_get_contents($cachePath));
                // otherwise we have no cache or new stuff and have to die horribly
                else
                {
                    print('Error: Flickr request came back empty');
                    print(' -> CURLOPT_URL / $restURL: ' . $restURL . ' -> ');
                    print(curl_error($ch) ? ' (' . curl_error($ch) . ')' : '');
                    return;
                }
            }

            curl_close($ch);

            // fetched new content: if non-error, update cache and return
            if (preg_match("/<err/", $result) == false)
            {
                $fp = fopen($cachePath, 'w');
                fwrite($fp, $result);
                fclose($fp);
                return($result);
            }
            // otherwise we again have to try to serve cache or die horribly
            else
            {
                if (file_exists($cachePath))
                    return(file_get_contents($cachePath));
                else
                {
                    print('Error: Flickr request came back with an error: ');
                    print(htmlspecialchars($result));
                    return;
                }
            }
                
        }
        // otherwise, cache is ok, at least we checked
        else
            return($result);
    }    
    
    function _startElement ($parser, $name, $attrs)
    {
        switch ($name)
        {
            case "err":
                if ($attrs['code'])
                {
                    print("<p><strong>Flickr reported an error: </strong>");
                    print("[" . $attrs['code'] . "] " . $attrs['msg'] . "</p>");
                }
                break;
            case "photo":
                $index = count($this->parseOutput);
                $this->parseOutput[$index]['id']     = $attrs['id'];
                $this->parseOutput[$index]['secret'] = $attrs['secret'];
                $this->parseOutput[$index]['server'] = $attrs['server'];
                $this->parseOutput[$index]['title']  = $attrs['title'];
                break;
            case "dates":
                $index = count($this->parseOutput);
                $this->parseOutput[$index]['datetaken'] = $attrs['taken'];
                $this->parseOutput[$index]['posted'] = $attrs['posted'];
                break;
            case "tag":
                $this->parseBookmark = 'tag';
                break;
            case "tags":
                $this->TagNumber = 0;
                break;
            case "title":
                $this->parseBookmark = 'title';
                break;
            case "description":
                $this->parseBookmark = 'description';
                break;
            default:
                break;
        }
    }
    
    function _endElement ($parser, $name)
    {
        $this->parseBookmark = '';
    }
    
    function _characterData ($parser, $data)
    {
        switch ($this->parseBookmark)
        {
            case "tag":
                $this->parseOutput[] = $data;
                $this->parseOutput['tags'][$this->TagNumber] = $data;
                $this->TagNumber++;
                break;
            case "title":
                $index = count($this->parseOutput);
                $this->parseOutput[$index]['title'] = $data;
                break;
            case "description":
                $index = count($this->parseOutput);
                $this->parseOutput[$index]['description'] = $data;
                break;
        }
    }
    
    function _parseXML ($data)
    {
        $this->parseOutput = array();

        $xml_parser = xml_parser_create();
    
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
    
        xml_set_element_handler($xml_parser, 
                                array(&$this, '_startElement'), 
                                array(&$this, '_endElement'));

        xml_set_character_data_handler($xml_parser, array(&$this, '_characterData'));
        
        if (strlen($data) == 0)
            print("Empty XML data");
        
        if (!xml_parse($xml_parser, $data, true))
        {
            print(sprintf("XML error: %s at line %d", 
                        xml_error_string(xml_get_error_code($xml_parser)),
                        xml_get_current_line_number($xml_parser)));
            print("\nXML data dump follows:\n\n");
            print($data);
        }
    
        xml_parser_free($xml_parser);
    }
    
    function fetchPhotoContents ($photoURL)
    {
        $cachePath = $this->cacheDir . '/photos/' . md5($photoURL);

        if (file_exists($cachePath))
            $contents = file_get_contents($cachePath);
        
        if ($this->_randomOneIn(10) == false)
            return($contents);
    
        $contents = file_get_contents($photoURL);
        
        if (strlen($contents) == 0 && file_exists($cachePath))
            return(file_get_contents($cachePath));

        if (file_exists($cachePath) == false && strlen($contents))
        {
            $fp = fopen($cachePath, 'w');
            fwrite($fp, $contents);
            fclose($fp);
        }

        return($contents);
    }
    
    function getTagList ()
    {
        $args = array("api_key"  => $this->apiKey, 
                      "method"   => 'flickr.tags.getListUser', 
                      "user_id"  => $this->userID);
        
        while (list($key, $val) = each($args))
            $arg_pairs[] = $key . '=' . urlencode($val);
            
        $restURL = $this->apiURL . '?' . join('&', $arg_pairs);
        
        $result = $this->_fetchREST($restURL);
        
        $this->_parseXML($result);
        
        return($this->parseOutput);
    }

    function searchForTags ($tags, $bool = 'all', $perPage = 100)
    {
        $args = array("api_key"  => $this->apiKey, 
                      "method"   => 'flickr.photos.search', 
                      "user_id"  => $this->userID, 
                      "tags"     => $tags,
                      "per_page" => $perPage, 
                      "tag_mode" => $bool);
        
        while (list($key, $val) = each($args))
            $arg_pairs[] = $key . '=' . urlencode($val);
            
        $restURL = $this->apiURL . '?' . join('&', $arg_pairs);
        
        $result = $this->_fetchREST($restURL);

        $this->_parseXML($result);
        
        return($this->parseOutput);
    }

    function searchFromTime ($time, $perPage = 100)
    {
        $args = array("api_key"  => $this->apiKey, 
                      "method"   => 'flickr.photos.search', 
                      "user_id"  => $this->userID, 
                      "min_upload_date"   => $time, 
                      "tag_mode" => $bool);
        
        while (list($key, $val) = each($args))
            $arg_pairs[] = $key . '=' . urlencode($val);
            
        $restURL = $this->apiURL . '?' . join('&', $arg_pairs);
        
        $result = $this->_fetchREST($restURL);

        $this->_parseXML($result);
        
        return($this->parseOutput);
    }


    
    function getPhotoInfo ($photoID)
    {
        $args = array("api_key"  => $this->apiKey, 
                      "method"   => 'flickr.photos.getInfo', 
                      "photo_id" => $photoID);
        
        while (list($key, $val) = each($args))
            $arg_pairs[] = $key . '=' . urlencode($val);
            
        $restURL = $this->apiURL . '?' . join('&', $arg_pairs);
        
        $result = $this->_fetchREST($restURL);

        $this->_parseXML($result);
        
        return($this->parseOutput);
    }

    function createSlideShowProXML ($photos, $title = '', $desc = '')
    {
        $xml  = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $xml .= '<gallery>' . "\n";
        $xml .= '<album title="' . $title;
        $xml .= '" description="' . $desc;
        $xml .= '" lgPath="modules/echo_slideshow/photo.php/"';
        $xml .= ' tn="modules/echo_slideshow/start.jpg"';
        $xml .= ' tnPath="modules/echo_slideshow/photo.php/tn/">' . "\n";
        
        for ($i = 0; $i < count($photos); $i++)
        {            
            $xml .= '<img src="' . $photos[$i] . '.jpg';            
            $xml .= '" link="modules/echo_slideshow/photo.php/';
            $xml .= $photos[$i] . '.jpg';
            $xml .= '" target="_blank" />' . "\n";
        }

        $xml .= '</album>' . "\n";
        $xml .= '</gallery>' . "\n";

        return($xml);
    }

    function get_UserInfo($userdata) {

      $args = array("api_key"  => $this->apiKey, 
                    "method"   => 'flickr.people.findByUsername', 
                    "username" => $userdata);      
      while (list($key, $val) = each($args))
          $arg_pairs[] = $key . '=' . urlencode($val);          
      $restURL = $this->apiURL . '?' . join('&', $arg_pairs);      
      $result = $this->_fetchREST($restURL);
      $this->_parseXML($result);
      return($this->parseOutput);
      
    }
}



?>
