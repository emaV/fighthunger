<?php

 /*
  * Electronic Payment XML Interface
  *
  * This stuff will most likely be placed into another file
  * in later revisions.
  *
  * $Id: eway.inc,v 1.2.2.1 2006/09/20 11:39:43 sammys Exp $
  *
  */

/* Call back Functions for the XML Parser */
function ep_xml_element_start ($parser, $tag, $attributes) {
  global $current_tag;
  $current_tag = $tag; 
}

function ep_xml_element_end ($parser, $tag) {
  global $current_tag;
  $current_tag = '';
}

function ep_xml_data ($parser, $cdata) {
  global $xml_data, $current_tag;
  if ($cdata[0] != "\n" && $cdata[0] != "\r") {
    $xml_data[$current_tag] .= $cdata;
  }
}

/*
 * Constructor
 *
 * @param $data Values used in the build and response functions. See 
 * $xml_build_fn and $xml_response_fn for more details.
 * @param $gateway_url URL to which the payment is communicated.
 * @param $xml_build_fn Function (in the gateway-specific module) that implements
 * the building of the xml syntax understood by the gateway. This function must
 * take just one parameter being a reference to data below (e.g &$data).
 * @param $xml_response_fn Function (in the gateway-specific module) that implements
 * the extraction of result data from the gateway's XML response. This function must
 * take exactly two parameters being a reference to $data and a reference to the XML
 * response (e.g &$data, &$response). The function must return TRUE when the
 * payment is successful and FALSE when the payment is not. $data must be filled
 * appropriately.
 *   - $data['t']->payment->cc_amount: amount of the transaction (float with 2 decimal places)
 *   - $data['t']->payment->cc_refnum: transaction reference number
 *   - $data['t']->payment->cc_ordernum: 
 *   - $data['t']->payment->cc_reason: error reason or successful payment message
 *   - $data['t']->payment->logmsg: a more verbose error message for use with watchdog
 */
function xml_payment (&$data, $gateway_url, $xml_build_fn, $xml_response_fn) {
  global $xml_data, $current_tag;

  $xml_data = array();
  $current_tag = '';

  /* First build the XML request using the supplied function */
  if (!function_exists($xml_build_fn)) {
    echo __FUNCTION__."(): XML build function '$xml_build_fn' not found! Aborting!";
    exit(0);
  } else if (!function_exists($xml_response_fn)) {
    echo __FUNCTION__."(): XML response function '$xml_response_fn' not found! Aborting!";
    exit(0);
  } else {
    $xml_request = $xml_build_fn($data);
  }

  /* Use CURL to execute XML POST and write output to the response variable */
  $ch = curl_init($gateway_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_request);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDSIZE, 0);
  curl_setopt($ch, CURLOPT_TIMEOUT, 360); 
  curl_setopt($ch, CURLOPT_SSLVERSION, 3);
  $xml_response = curl_exec($ch); /// execute the curl session and return the output to a variable $datastream 
  $xml_response = str_replace(" standalone=\"yes\"", "", $xml_response);
  curl_close($ch);
  $parser = xml_parser_create();

  /* Disable XML tag capitalisation (Case Folding) */
  xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, FALSE);

  /* Define Callback functions for XML Parsing */
  //xml_set_object($this->parser, &$this);
  xml_set_element_handler($parser, "ep_xml_element_start", "ep_xml_element_end");
  xml_set_character_data_handler($parser, "ep_xml_data");

  /* Parse the XML response */
  if (!xml_parse($parser, $xml_response, TRUE)) {
    echo 'XML parsing failed: '.xml_error_string(xml_get_error_code($parser));
  }

  /* Clean up after ourselves */
  xml_parser_free($parser);

  $logmsg = '';
  $result = $xml_response_fn($data, $xml_data, $logmsg);
  
  unset($xml_data);
  unset($current_tag);

  if (!empty($data['t']->payment->logmsg)) {
    watchdog('xml_payment', $data['t']->payment->logmsg, WATCHDOG_ERROR);
  }

  return $result;
}

/**
 * Returns an array containing the name split into components: title, first_name,
 * initial and last_name. The function can handle the following different name
 * formats:
 *   - Fred Bloggs
 *   - Mr Fred Bloggs
 *   - Fred M Bloggs
 *   - Mr Fred M Bloggs
 *   - Mr Fred Olaf M Bloggs
 *   - Mr F M Bloggs
 *
 * If the function has any trouble, the name passed in will be the one returned
 * as first_name.
 *
 * @param $name Name that is to be split. This string is trim()'ed first.
 * @return Array containing the keys: title, first_name, initial and last_name.
 */
function payment_cc_split_name($name) {
  $m = array();
  $tname = trim($name);
  $title = $first_name = $initial = $last_name = '';
  if (preg_match('/^((mr|mrs|miss|dr)\s)?(\w+(\s(\w{2,}))*)(\s(\w))?\s(\w+)$/i', $tname, $m)) {
    $title = $m[2];
    $first_name = $m[3];
    $initial = $m[7];
    $last_name = $m[8];
  } else if (preg_match('/^(.+)\s(\w+)$/', $tname, $m)) {
    $first_name = $m[1];
    $last_name = $m[2];
  } else {
    $first_name = $name;
  }

  return array('title' => $title, 'first_name' => $first_name, 'initial' => $initial, 'last_name' => $last_name);
}

?>
