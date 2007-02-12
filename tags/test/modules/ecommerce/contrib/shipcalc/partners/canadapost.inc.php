<?php
// $Id: canadapost.inc,v 1.1.2.2 2006/09/07 19:09:48 nedjo Exp $

/**
 * @file
 * Functions to communicate with CanadaPost API.
 *
 * Derived from the CanadaPost document titled:
 *    Canada Post - Sell Online Developer's Site
 *    Version: 12 (Sept. 15, 2003)
 *
 * Additional information:
 *  http://206.191.4.228/
 *
 * Created by Chris Geoghegan (chris@imagexmedia.com)
 */

/**
 * Shipcalc _shipping_methods hook.
 *
 * Define the CanadaPost shipping methods.
 */
function canadapost_shipping_methods($type = 'domestic') {
  // TODO: Add descriptions of various shipping methods.
  $methods = array();

  $methods['canadapost'] = array(
    '#title' => t('CanadaPost'),
    '#description' => t('CanadaPost')
  );
  $methods['canadapost']['PC'] = array(
    '#title' => t('Priority Courier'),
  );
  $methods['canadapost']['XP'] = array(
    '#title' => t('XPresspost'),
  );
  $methods['canadapost']['EX'] = array(
    '#title' => t('Expedited'),
  );
  $methods['canadapost']['RP'] = array(
    '#title' => t('Regular'),
  );
  $methods['canadapost']['USPC'] = array(
    '#title' => t('US Purolator Courier'),
  );
  $methods['canadapost']['USXP'] = array(
    '#title' => t('Xpresspost USA'),
  );
  $methods['canadapost']['USEC'] = array(
    '#title' => t('Expedited US Commercial'),
  );
  $methods['canadapost']['USEB'] = array(
    '#title' => t('Expedited US Business'),
  );
  $methods['canadapost']['SPA'] = array(
    '#title' => t('Small Packets Air'),
  );
  $methods['canadapost']['SPS'] = array(
    '#title' => t('Small Packets Surface'),
  );

  return $methods;
}

/**
 * Shipcalc _settings_form hook.
 *
 * Create a form for CANADAPOST-specific configuration.
 */
function canadapost_settings_form(&$form) {
  $form['canadapost'] = array(
    '#type' => 'fieldset',
    '#title' => t('CanadaPost settings')
  );
  $form['canadapost']['canadapost_merchantid'] = array(
    '#type' => 'textfield',
    '#title' => t('CanadaPost Merchant ID'),
    '#description' => t('Your unique CanadaPost Merchant ID is provided when you %register for the CanadaPost VentureOne Account (free).', array('%register' => l(t('register'), url('http://www.canadapost.com/business/intsol/sb/ventureone/default-e.asp?source=web')))),
    '#default_value' => variable_get('shipcalc_canadapost_merchantid', ''),
    '#required' => TRUE
  );
  $form['canadapost']['canadapost_postalcode'] = array(
    '#type' => 'textfield',
    '#title' => t('Potal Code'),
    '#description' => t('This is the Postal Code of your business and is used for calculating shipping costs.'),
    '#default_value' => variable_get('shipcalc_canadapost_postalcode', ''),
    '#required' => TRUE
  );
  $form['canadapost']['canadapost_turnaround'] = array(
    '#type' => 'textfield',
    '#title' => t('Turn Around Time'),
    '#description' => t('This is the turn around time between receiving the order and shipping the product. (ie. enter 24 for a 24 hour turn around time.)'),
    '#default_value' => variable_get('shipcalc_canadapost_turnaround', ''),
    '#required' => TRUE
  );

  $form['canadapost']['canadapost_url'] = array(
    '#type' => 'textfield',
    '#title' => t('CanadaPost Server URL'),
    '#description' => t('Enter the fully qualified URL of the CanadaPost shipping rate server, as provided by CanadaPost.'),
    '#default_value' => (variable_get('shipcalc_canadapost_url', '') ? variable_get('shipcalc_canadapost_url', '') : 'http://206.191.4.228:30000'),
    '#required' => TRUE
  );

  // TODO: Testing to help admin set up site.  Not fully implemented yet.
  $form['canadapost']['test'] = array(
    '#type' => 'fieldset',
    '#title' => t('Testing'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE
  );
  $form['canadapost']['test']['canadapost_test_url'] = array(
    '#type' => 'textfield',
    '#title' => t('CanadaPost Test Server URL'),
    '#description' => t('CanadaPost provides a test server to test your site configuration prior to launch. Clicking <em>Test configuration</em> below will use your Access Key, User ID and Password to test several transactions against the CanadaPost Test Server URL.'),
    '#default_value' => (variable_get('shipcalc_canadapost_test_url', '') ? variable_get('shipcalc_canadapost_test_url', '') : 'http://206.191.4.228:30000'),
    '#required' => TRUE
  );

  $form['canadapost']['test']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Test Canada Post configuration')
  );
}

/**
 * Shipcalc _settings_form_submit hook.
 *
 * Save data from our CanadaPost-specific configuration form.
 */
function canadapost_settings_form_submit(&$form) {
  global $form_values;
  $op = $_POST['op'];

  if ($form_values['shipping_partner'] == 'canadapost') {
    variable_set('shipcalc_canadapost_merchantid', $form_values['canadapost_merchantid']);
    variable_set('shipcalc_canadapost_postalcode', $form_values['canadapost_postalcode']);
    variable_set('shipcalc_canadapost_turnaround', $form_values['canadapost_turnaround']);
    variable_set('shipcalc_canadapost_url', $form_values['canadapost_url']);
    variable_set('shipcalc_canadapost_test_url', $form_values['canadapost_test_url']);
  }

  if ($op == t('Test Canada Post configuration')) {
    // Populate a fake transfer.
    // Find the first 'shippable' product type.
    $ptypes = product_get_ptypes();
    foreach (array_keys($ptypes) as $ptype) {
      if (product_is_shippable(NULL, $ptype)) {
        // Load the first product of this type.
        $nid = db_result(db_query(db_rewrite_sql('SELECT n.nid FROM {node} n INNER JOIN {ec_product} p ON n.nid = p.nid WHERE p.ptype = "%s" LIMIT 1', $ptype)));
        if ($nid) {
          $node = node_load($nid);
        }
        break;
      }
    }
    if (!$node) {
      drupal_set_message(t('To test UPS configuration you must first <a href="/node/add/product/%ptype">create a %ptype_nice</a> and assign it shippable properties.', array('%ptype' => $ptype, '%ptype_nice' => $ptypes[$ptype])));
      return;
    }
    $txn = new StdClass();
    $txn->items[] = $node;
    $txn->address['shipping']->zip = 33068;
    $txn->address['shipping']->country = 'US';
    $rates = canadapost_get_rates($txn, 'http://206.191.4.228:30000', TRUE);
    drupal_set_message(theme('shipcalc_testing_results', $rates));
  }
}

/**
 * Shipcalc _product_attributes hook.
 *
 * Update the product form with fields that we need.  It is possible for 
 * multiple carriers to define the same field -- that is fine.  So long as
 * the field as the same name (e.g. 'weight'), it will only be displayed
 * once, and the data will be saved and restored for use by all shipping
 * partners that define it.
 */
function canadapost_product_attributes($form) {
  $fields = array();
  $fields['weight'] = array(
    '#type' => 'textfield',
    '#title' => t('Product Weight'),
    '#description' => t('The weight of the product (in Kilograms)'),
    '#default_value' => $form['#node']->product_attributes['weight']
  );
  
  $fields['width'] = array(
    '#type' => 'textfield',
    '#title' => t('Product Width'),
    '#description' => t('The width of the product (in Centimeters)'),
    '#default_value' => $form['#node']->product_attributes['width']
  );
  
  $fields['height'] = array(
    '#type' => 'textfield',
    '#title' => t('Product Height'),
    '#description' => t('The height of the product (in Centimeters)'),
    '#default_value' => $form['#node']->product_attributes['height']
  );
  
  $fields['length'] = array(
    '#type' => 'textfield',
    '#title' => t('Product Length'),
    '#description' => t('The length of the product (in Centimeters).'),
    '#default_value' => $form['#node']->product_attributes['length']
  );
  
  return $fields;
}

/**
 * Shipcalc _get_rates_form hook.
 *
 * Request rates from UPS for the current transaction.  Return a form of all
 * shipping options that the shipcalc module will display during the checkout 
 * process.
 */
function canadapost_get_rates($txn, $url = 'DEFAULT', $testing = FALSE) {
  $rates = array();

  if ($url == 'DEFAULT') {
    $url = variable_get('shipcalc_canadapost_url', 'http://206.191.4.228:30000');
  }

  $xml = canadapost_AccessRequest($txn);

  // We're doing a POST, so no need for libcurl.
  $result = _canadapost_http_request("http://206.191.4.228:30000", array('Content-type' => 'text/xml'), 'POST', $xml);  

  /**
   * Ugly hack to work around PHP bug, details here:
   *   http://bugs.php.net/bug.php?id=23220
   * We strip out errors that look something like:
   *  warning: fread() [function.fread]: SSL fatal protocol error in...
   */
  $messages = drupal_set_message();
  $errors = $messages['error'];
  $count = 0;
  for ($i = 0; $i <= sizeof($errors); $i++) {
    if (strpos($errors[$i], 'SSL: fatal protocol error in')) {
      unset($errors[$i]);
      unset($_SESSION['messages']['error'][$i]);
    }
    else {
      $count++;
    }
  }
  if (!$count) {
    unset($_SESSION['messages']['error']);
  }
  // End of ugly hack.

  $response = _parse_xml($result->data, '<eparcel>');
  $code = _parse_xml($response, '<statusCode>');
  $rates = array();
  if ($code == 0) { // failed request 
    $error = _parse_xml($response, '<statusMessage>');
    drupal_set_message(t($error), 'error');
    
    return -1; // negative charges indicates an error
  } else { // success, build form
  	$xml = $result->data;
    $loop = TRUE;
    $options = array();
    
    $xml = _parse_xml($response, '<ratesAndServicesResponse>');
    
    $xml = ereg_replace (" id=\"[0-9]{4}\"", "", $xml);
    $xml = ereg_replace (" sequence=\"[0-9]{1}\"", "", $xml);
        
    while ($loop == TRUE) {
      if (strpos($xml, '<product>')) {
      	$product = _parse_xml($xml, '<product>');
        // See if this is a supported shipping method.
        //TODO: make service checker work
        $service = _parse_xml($product, '<name>');
        if ($method = _canadapost_valid_service_code($service, $txn, $testing)) {
          $total = _parse_xml($product, '<rate>');
          //$currency = _parse_xml($total, '<CurrencyCode>');
          $currency = "CAD";
          //$value = _parse_xml($total, '<MonetaryValue>');
          $shipping_date = _parse_xml($product, '<shippingDate>');
          $delivery_date = _parse_xml($product, '<deliveryDate>');
          $delivery_day = _parse_xml($product, '<deliveryDayOfWeek>');
          $next_day_am = _parse_xml($product, '<nextDayAM>');
          $packing_id = _parse_xml($product, '<packingID>');

          $rates[] = array(
            '#service' => 'canadapost',
            '#key' => key($method),
            '#cost' => $total,
            '#currency' => $currency,
            '#method' => current($method),
            '#shipping_date' => $shipping_date,
            '#delivery_date' => $delivery_date,
            '#delivery_day' => $delivery_day,
            '#next_day_am' => $next_day_am,
            '#packing_id' => $packing_id
          );
        }
        $xml = substr($xml, strpos($xml, '</product>') + 1);
      }
      else {
        $loop = FALSE;
      }
    }
  }

  return $rates;
}

/**
 * Build the XML AccessRequest used to login to the UPS shipping server.
 */
function canadapost_AccessRequest($txn) {
  $xml = "<?xml version=\"1.0\"?>\n";
  $xml .= "<!DOCTYPE eparcel SYSTEM \"eParcel.dtd\">\n";
  $xml .= "<eparcel>\n";
  $xml .= "<language>en</language>\n";
  $xml .= "<ratesAndServicesRequest>\n";
  $xml .= "<merchantCPCID>".variable_get('shipcalc_canadapost_merchantid', '')."</merchantCPCID>\n";
  $xml .= "<fromPostalCode>".variable_get('shipcalc_canadapost_postalcode', '')."</fromPostalCode>\n";
  $xml .= "<turnAroundTime>".variable_get('shipcalc_canadapost_turnaround', '')."</turnAroundTime>\n";
  
  //TODO: this field is optional
  //<itemsPrice>  {Insert items $ here} </itemsPrice>
    
  $xml .= "<lineItems>\n";
  
  $weight = 0;
  if (is_array($txn->items) && $txn->items != array()) {
    foreach ($txn->items as $item) {
      // Load product_weight into $item.
      shipping_nodeapi($item, 'load', NULL);
      if ($item->product_attributes['weight']) {
        $weight = $item->product_attributes['weight'];
        $length = $item->product_attributes['length'];
        $height = $item->product_attributes['height'];
        $width = $item->product_attributes['width'];
        
        $xml .= "<item>\n";
      	$xml .= "<quantity>1</quantity>\n"; //FIX THIS
        $xml .= "<weight>$weight</weight>\n";
        $xml .= "<length>$length</length>\n";
        $xml .= "<width>$width</width>\n";
        $xml .= "<height>$height</height>\n";
        $xml .= "<description>Item</description>\n"; //FIX THIS
        $xml .= "</item>\n";
        
      }
    }
  }
  
  $xml .= "</lineItems>\n";

	$xml .= "<city>".$txn->address['shipping']->city."</city>\n";
	$xml .= "<provOrState>".$txn->address['shipping']->state."</provOrState>\n";
  $xml .= "<country>".$txn->address['shipping']->country."</country>\n";
  $xml .= "<postalCode>".$txn->address['shipping']->zip."</postalCode>\n";

	$xml .= "</ratesAndServicesRequest>\n";
	$xml .= "</eparcel>\n";

  return $xml;
}

/**
 * Internal helper function, not yet complete.  CANADAPOST returns an array
 * possible shipping methods.  We need to only return the ones that are 
 * configured for use with the current item.
 *
 */
function _canadapost_valid_service_code($code, $txn, $testing = FALSE) {
  
  switch ($code) {
    case "Priority Courier":
    	$method = array('PC' => t('Priority Courier'));
      break;
    case "XPresspost":
      $method = array('XP' => t('XPresspost'));
      break;
    case "Regular":
      $method = array('RP' => t('Regular'));
      break;
    case "Expedited":
      $method = array('EX' => t('Expedited'));
      break;
    case "US Purolator Courier":
      $method = array('USPC' => t('US Purolator Courier'));
      break;
      case "Xpresspost USA":
      $method = array('USXP' => t('Xpresspost USA'));
      break;
      case "Expedited US Commercial":
      $method = array('USEC' => t('Expedited US Commercial'));
      break;
      case "Expedited US Business":
      $method = array('USEB' => t('Expedited US Business'));
      break;
      case "Small Packets Air":
      $method = array('SPA' => t('Small Packets Air'));
      break;
      case "Small Packets Surface":
      $method = array('SPS' => t('Small Packets Surface'));
      break;
  }

  if ($testing) {
    return $method;
  }

  // TODO:  This is an ugly hack to see if the current method is one of the
  //  supported methods found in $item->shipping_mehtods for any item in the
  //  order.  This should be updated to use the shipping module's 
  //  shipping_method_filter() if possible.  This becomes more important when
  //  the shipping module gains per-item shipping capabilities.
  
  if (is_array($txn->items)) {
    foreach ($txn->items as $item) {
      if (is_array($item->shipping_methods)) {
        foreach ($item->shipping_methods as $supported) {
          if (in_array(key($method), $supported)) {
            return $method;
          }
        }
      }
    }
  }
  return NULL;
}

function _canadapost_http_request($url, $headers = array(), $method = 'GET', $data = NULL, $retry = 3) {
  $result = new StdClass();

  // Parse the URL, and make sure we can handle the schema.
  $uri = parse_url($url);
  switch ($uri['scheme']) {
    case 'http':
      $port = isset($uri['port']) ? $uri['port'] : 80;
      $host = $uri['host'] . ($port != 80 ? ':'. $port : '');
      $fp = @fsockopen($uri['host'], $port, $errno, $errstr, 15);
      break;
    case 'https':
      // Note: Only works for PHP 4.3 compiled with OpenSSL.
      $port = isset($uri['port']) ? $uri['port'] : 443;
      $host = $uri['host'] . ($port != 443 ? ':'. $port : '');
      $fp = @fsockopen('ssl://'. $uri['host'], $port, $errno, $errstr, 20);
      break;
    default:
      $result->error = 'invalid schema '. $uri['scheme'];
      return $result;
  }

  // Make sure the socket opened properly.
  if (!$fp) {
    $result->error = trim($errno .' '. $errstr);
    return $result;
  }

  // Construct the path to act on.
  $path = isset($uri['path']) ? $uri['path'] : '/';
  if (isset($uri['query'])) {
    $path .= '?'. $uri['query'];
  }

  // Create HTTP request.
  $defaults = array(
    // RFC 2616: "non-standard ports MUST, default ports MAY be included".
    // We don't add the port to prevent from breaking rewrite rules checking
    // the host that do not take into account the port number.
    'Host' => "Host: $host",
    'User-Agent' => 'User-Agent: Drupal (+http://drupal.org/)',
    'Content-Length' => 'Content-Length: '. strlen($data)
  );
  
  foreach ($headers as $header => $value) {
    $defaults[$header] = $header .': '. $value;
  }

  $request = $method .' '. $path ." HTTP/1.0\r\n";
  $request .= implode("\r\n", $defaults);
  $request .= "\r\n\r\n";
  if ($data) {
    $request .= $data ."\r\n";
  }
  $result->request = $request;

  fwrite($fp, $request);

  // Fetch response.
  $response = '';
  while (!feof($fp) && $chunk = fread($fp, 1024)) {
    $response .= $chunk;
  }
  fclose($fp);
  
  // Parse response.
  list($split, $result->data) = explode("<?xml", $response, 2);
  $split = preg_split("/\r\n|\n|\r/", $split);
  $result->data = "<?xml".$result->data;

  list($protocol, $code, $text) = explode(' ', trim(array_shift($split)), 3);
  $result->headers = array();

  // Parse headers.
  while ($line = trim(array_shift($split))) {
    list($header, $value) = explode(':', $line, 2);
    if (isset($result->headers[$header]) && $header == 'Set-Cookie') {
      // RFC 2109: the Set-Cookie response header comprises the token Set-
      // Cookie:, followed by a comma-separated list of one or more cookies.
      $result->headers[$header] .= ','. trim($value);
    }
    else {
      $result->headers[$header] = trim($value);
    }
  }

  $responses = array(
    100 => 'Continue', 101 => 'Switching Protocols',
    200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content',
    300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 307 => 'Temporary Redirect',
    400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Time-out', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Large', 415 => 'Unsupported Media Type', 416 => 'Requested range not satisfiable', 417 => 'Expectation Failed',
    500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Time-out', 505 => 'HTTP Version not supported'
  );
  // RFC 2616 states that all unknown HTTP codes must be treated the same as
  // the base code in their class.
  if (!isset($responses[$code])) {
    $code = floor($code / 100) * 100;
  }

  switch ($code) {
    case 200: // OK
    case 304: // Not modified
      break;
    case 301: // Moved permanently
    case 302: // Moved temporarily
    case 307: // Moved temporarily
      $location = $result->headers['Location'];

      if ($retry) {
        $result = drupal_http_request($result->headers['Location'], $headers, $method, $data, --$retry);
        $result->redirect_code = $result->code;
      }
      $result->redirect_url = $location;

      break;
    default:
      $result->error = $text;
  }

  $result->code = $code;
  return $result;
}
