<?php
/**
 * Mini XML Library
 * 
 * Data conversion functions and very basic xml parser. 
 * It wont handle attributes.
 * Developed by Jose A. Reyero
 * http://www.reyero.net
 */

function xml_data2xml($data){
  if(is_object($data)){
    return xml_array2xml(get_object_vars($data));
  } elseif(is_array($data)){
    return xml_array2xml($data);
  } else {
    return $data;
  }
}

function xml_array2xml($data){
  $out = '';
  foreach($data as $name => $value){
    // If name is numeric, do not create tag
    $out .= is_numeric($name) ? xml_data2xml($value) : "<$name>".xml_data2xml($value)."</$name>";
  }
  return $out;
}

function xml_extract_tag($data, $tag, $single = TRUE){
  $data = str_replace("\n", '', $data);
  if(preg_match_all("/(<$tag>.*<\/$tag>)/m", $data, $matches) ) { // Ungreedy, multiline
    return $single ? $matches[1][0] : $matches[1];
  }
}
/**
 * Convert xml in PHP object
 * TO-DO: Check for xml errors
 */

function xml_xml2object($data){
  $parser = xml_parser_create();
  xml_parse_into_struct($parser, $data, $values, $index);
  xml_parser_free($parser);
  
  // Fetch values array into object
  $current = NULL;
  $stack = array();
  $level = 1;
  foreach($values as $item){
    if($item['level'] == 1) continue; // This is the top level enclosing tag
    $name = strtolower($item['tag']);
    switch($item['type']){
      case 'open':
          array_push($stack, $current);
          $current = new StdClass();
        break;
      case 'complete';
        $current->$name = $item['value'];
        break;
      case 'close';
        $parent = array_pop($stack);
        isset($parent->$name) ? array_push($parent->$name, $current) : $parent->$name = array($current);
        $current = $parent;
        break;
    }
  } 
  return $current; 
}

/**
 * Debugging
 * TO-DO: Remove
 */

function xml_debug_array($data, $title = 'array'){
  $output ="<h2>$title</h2>";
  $output .= '<pre>';
  $output .= print_r($data, TRUE);
  $output .= '</pre>';
  return $output;
}
function xml_debug_xml($data, $title = 'xml'){
  $output ="<h2>$title</h2>";
  $data = str_replace('>', "> ", $data);
  $output .= htmlentities($data);
  return $output;
}
?>