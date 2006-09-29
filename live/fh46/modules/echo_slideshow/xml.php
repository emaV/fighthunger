<?php

require('FlickrLib.php');

$flickr = new FlickrLib('b9937eed5e47e753b717ddd7a2045bc7', '62728578@N00');

$tags = $_GET['tags'];

mysql_connect('db.echoditto.com', 'wtw_www', '87sHsnMb');
mysql_select_db('wtw_www');

$photos = array();

$query  = "SELECT DISTINCT photo_id 
           FROM wtw_gallery_tags 
           WHERE ";

if (preg_match("/\s/", $tags))
{
    $tag_array = preg_split("/\s+/", $tags);
    $query .= "tag = " . $tag_array[0];
    for ($i = 0; $i < count($tag_array); $i++)
        $query .= " OR tag = '" . $tag_array[$i] . "'";
}
else
    $query .= "tag = '$tags'";
    
$result = mysql_query($query);

while ($row = mysql_fetch_array($result))
    $photos[] = $row['photo_id'];

$xml = $flickr->createSlideShowProXML($photos);

header("Content-type: text/xml");
print($xml);

?>
