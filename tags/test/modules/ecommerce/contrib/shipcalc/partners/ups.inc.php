<?php
// $Id: ups.inc,v 1.1.2.8 2006/11/06 04:27:16 nedjo Exp $

/**
 * @file
 * Functions to communicate with UPS API.
 *
 * Derived from the UPS document titled:
 *    UPS OnLine(r) Tools
 *    Developer's Guide
 *    Rates and Service Selection
 *    HTML Tool
 *    Version: 2004
 * 
 * And the document titled:
 *    UPS Online Tools
 *    Rates & Services Selection
 *    Programming Information
 *    (c) 2005
 *
 * Additional information:
 *  http://www.ups.com/content/us/en/bussol/offering/technology/
 *         automated_shipping/online_tools.html
 */

/**
 * Shipcalc _shipping_methods hook.
 *
 * Define the UPS shipping methods.
 */
function ups_shipping_methods($type = 'domestic') {
  // TODO: Add descriptions of various shipping methods.
  $methods = array();

  $methods['ups'] = array(
    '#title' => t('UPS'),
    '#description' => t('United Parcel Service of America, Inc.')
  );
  $methods['ups']['1DM'] = array(
    '#title' => t('Next Day Air Early AM'),
  );
  $methods['ups']['1DA'] = array(
    '#title' => t('Next Day Air'),
  );
  $methods['ups']['1DP'] = array(
    '#title' => t('Next Day Air Saver'),
  );
  $methods['ups']['2DM'] = array(
    '#title' => t('2nd Day Air AM'),
  );
  $methods['ups']['2DA'] = array(
    '#title' => t('2nd Day Air'),
  );
  $methods['ups']['3DS'] = array(
    '#title' => t('3 Day Select'),
  );
  $methods['ups']['GND'] = array(
    '#title' => t('Ground'),
  );
  $methods['ups']['STD'] = array(
    '#title' => t('Canada Standard'),
  );
  $methods['ups']['XPR'] = array(
    '#title' => t('Worldwide Express'),
  );
  $methods['ups']['XDM'] = array(
    '#title' => t('Worldwide Express Plus'),
  );
  $methods['ups']['XDP'] = array(
    '#title' => t('Worldwide Expedited')
  );

  return $methods;
}

/**
 * Shipcalc _settings_form hook.
 *
 * Create a form for UPS-specific configuration.
 */
function ups_settings_form(&$form) {
  $form['ups'] = array(
    '#type' => 'fieldset',
    '#title' => t('UPS settings')
  );
  $form['ups']['ups_accesskey'] = array(
    '#type' => 'textfield',
    '#title' => t('UPS Access Key'),
    '#description' => t('Your unique UPS Access Key is provided when you %register for the UPS Online Tools.', array('%register' => l(t('register'), url('http://www.ups.com/content/us/en/bussol/offering/technology/automated_shipping/online_tools.html')))),
    '#default_value' => variable_get('shipcalc_ups_accesskey', ''),
    '#required' => TRUE
  );
  $form['ups']['ups_userid'] = array(
    '#type' => 'textfield',
    '#title' => t('UPS User ID'),
    '#description' => t('Your unique UPS User ID is provided when you %register for the UPS Online Tools.', array('%register' => l(t('register'), url('http://www.ups.com/content/us/en/bussol/offering/technology/automated_shipping/online_tools.html')))),
    '#default_value' => variable_get('shipcalc_ups_userid', ''),
    '#required' => TRUE
  );
  $form['ups']['ups_password'] = array(
    '#type' => 'textfield',
    '#title' => t('UPS Password'),
    '#description' => t('Your unique UPS password is provided when you %register for the UPS Online Tools.', array('%register' => l(t('register'), url('http://www.ups.com/content/us/en/bussol/offering/technology/automated_shipping/online_tools.html')))),
    '#default_value' => variable_get('shipcalc_ups_password', ''),
    '#required' => TRUE
  );

  $form['ups']['ups_url'] = array(
    '#type' => 'textfield',
    '#title' => t('UPS Server URL'),
    '#description' => t('Enter the fully qualified URL of the UPS shipping rate server, as provided by UPS.'),
    '#default_value' => (variable_get('shipcalc_ups_url', '') ? variable_get('shipcalc_ups_url', '') : 'https://www.ups.com/ups.app/xml/Rate'),
    '#required' => TRUE
  );

  // TODO: Testing to help admin set up site.  Not fully implemented yet.
  $form['ups']['test'] = array(
    '#type' => 'fieldset',
    '#title' => t('Testing'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE
  );
  $form['ups']['test']['ups_test_url'] = array(
    '#type' => 'textfield',
    '#title' => t('UPS Test Server URL'),
    '#description' => t('UPS provides a Customer Integration Environment used to test your site configuration prior to launch.  Enter the fully qualified URL of the UPS Customer Integration Environment shipping rate server.  Clicking <em>Test configuration</em> below will use your Access Key, User ID and Password to test several transactions against the UPS Test Server URL.'),
    '#default_value' => (variable_get('shipcalc_ups_test_url', '') ? variable_get('shipcalc_ups_test_url', '') : 'https://wwwcie.ups.com/ups.app/xml/Rate'),
    '#required' => TRUE
  );

  $form['ups']['test']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Test ups configuration')
  );
}

/**
 * Shipcalc _settings_form_submit hook.
 *
 * Save data from our UPS-specific configuration form.
 */
function ups_settings_form_submit(&$form) {
  global $form_values;
  $op = $_POST['op'];

  if ($form_values['shipping_partner'] == 'ups') {
    variable_set('shipcalc_ups_accesskey', $form_values['ups_accesskey']);
    variable_set('shipcalc_ups_userid', $form_values['ups_userid']);
    variable_set('shipcalc_ups_password', $form_values['ups_password']);
    variable_set('shipcalc_ups_url', $form_values['ups_url']);
    variable_set('shipcalc_ups_test_url', $form_values['ups_test_url']);
  }

  if ($op == t('Test ups configuration')) {
    // Populate a fake transfer.
    // Find the first 'shippable' product type.
    $ptypes = product_get_ptypes();
    foreach (array_keys($ptypes) as $ptype) {
      if (product_is_shippable(NULL, $ptype)) {
        // Load the first product of this type.
        $nid = db_result(db_query(db_rewrite_sql("SELECT n.nid FROM {node} n INNER JOIN {ec_product} p ON n.nid = p.nid WHERE p.ptype = '%s' LIMIT 1"), $ptype));
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
    $rates = ups_get_rates($txn, 'https://wwwcie.ups.com/ups.app/xml/Rate', TRUE);
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
function ups_product_attributes($form) {
  $fields = array();
  $fields['weight'] = array(
    '#type' => 'textfield',
    '#title' => t('Product weight'),
    '#description' => t('The weight of the product (in %unit)', array('%unit'=> (variable_get('shipcalc_units', 'LBS')) ? t('pounds') : t('kilograms'))),
    '#default_value' => $form['#node']->product_attributes['weight']
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
function ups_get_rates($txn, $url = 'DEFAULT', $testing = FALSE) {
  $rates = array();
  if ($url == 'DEFAULT') {
    $url = variable_get('shipcalc_ups_url', 'https://www.ups.com/ups.app/xml/Rate');
  }

  $xml = ups_AccessRequest();
  $xml .= ups_RatingServiceSelectionRequest($txn);

  // We're doing a POST, so no need for libcurl.
  $result = drupal_http_request($url, array('Content-type' => 'text/xml'), 'POST', $xml);

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

  $response = _parse_xml($result->data, '<Response>');
  $code = _parse_xml($response, '<ResponseStatusCode>');
  if ($code == 0) { // failed request 
    $error = _parse_xml($response, '<Error>');
    $error_severity = _parse_xml($error, '<ErrorSeverity>');
    $error_code = _parse_xml($error, '<ErrorCode>');
    $error_description = _parse_xml($error, '<ErrorDescription>');
    drupal_set_message(t('%description (%severity error %code)', array('%severity' => $error_severity, '%code' => $error_code, '%description' => $error_description)), 'error');
    // Try and offer a helpful error, useful for initial configuration.
    $location = split('/', _parse_xml($error, '<ErrorLocationElementName>'));
    if ($location[0] == 'AccessRequest') {
      switch ($location[1]) {
        case 'UserId':
          drupal_set_message(t('There is an error with your <em>User ID</em>, please verify that you have entered it correctly and try again.'), 'error');
          break;
        case 'Password':
          drupal_set_message(t('There is an error with your <em>Password</em>, please verify that you have entered it correctly and try again.'), 'error');
          break;
      }
    }
    return -1; // negative charges indicates an error
  }
  else { // success, build form
    $xml = $result->data;
    $loop = TRUE;
    $options = array();
    while ($loop == TRUE) {
      if (strpos($xml, '<RatedShipment>')) {
        $rate = _parse_xml($xml, '<RatedShipment>');

        // See if this is a supported shipping method.
        $service = _parse_xml($rate, '<Service>');
        $service_code = _parse_xml($service, '<Code>');
        if ($method = _ups_valid_service_code($service_code, $txn, $testing)) {
          $total = _parse_xml($rate, '<TotalCharges>');
          $currency = _parse_xml($total, '<CurrencyCode>');
          $value = _parse_xml($total, '<MonetaryValue>');

          $rates[] = array(
            '#service' => 'ups',
            '#key' => key($method),
            '#cost' => $value,
            '#currency' => $currency,
            '#method' => current($method)
          );
        }
        $xml = substr($xml, strpos($xml, '</RatedShipment>') + 1);
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
function ups_AccessRequest() {
  $xml = "<?xml version=\"1.0\"?>\n";
  $xml .= "<AccessRequest xml:lang=\"en-US\">\n";
  $xml .= "  <AccessLicenseNumber>". variable_get('shipcalc_ups_accesskey', '') ."</AccessLicenseNumber>\n";
  $xml .= "  <UserId>". variable_get('shipcalc_ups_userid', '') ."</UserId>\n";
  $xml .= "  <Password>". variable_get('shipcalc_ups_password', '') ."</Password>\n";
  $xml .= "</AccessRequest>\n";
  return $xml;
}

/**
 * Build the XML RatingServiceSelectionRequest used to make a request from the
 * UPS shipping server.
 */
function ups_RatingServiceSelectionRequest($txn) {
  $xml = "<?xml version=\"1.0\"?>\n";
  $xml .= "<RatingServiceSelectionRequest xml:lang=\"en-US\">\n";
  $xml .= "  <Request>\n";
  $xml .= "    <TransactionReference>\n";
  $xml .= "      <CustomerContext>Shipcalc Request</CustomerContext>\n";
  $xml .= "      <XpciVersion>1.0001</XpciVersion>\n";
  $xml .= "    </TransactionReference>\n";
  $xml .= "    <RequestAction>Rate</RequestAction>\n";
  //$xml .= "    <RequestOption>Rate</RequestOption>\n";
  $xml .= "    <RequestOption>shop</RequestOption>\n";
  $xml .= "  </Request>\n";
  $xml .= "  <PickupType>\n";
  // TODO: Make Pickup Type Code configurable.
/*
  Default value is 01. Valid values are:
    01 - Daily Pickup
    03 - Customer Counter
    06 - One Time Pickup
    07 - On Call Air
    11 - Suggested Retail Rates
    19 - Letter Center
    20 - Air Service Center
*/
  $xml .= "    <Code>01</Code>\n";
  $xml .= "  </PickupType>\n";
  // TODO: Optionally support CustomerClassification/Code (for now use defaults)
  $xml .= "  <Shipment>\n";
  $xml .= "    <Shipper>\n";
  $xml .= "      <Address>\n";
  $address = shipping_default_shipfrom();
  if (isset($address['code'])) {
    $xml .= "        <PostalCode>". $address['code'] ."</PostalCode>\n";
    $xml .= "        <CountryCode>". strtoupper($address['country']) ."</CountryCode>\n";
  }
  else {
    $xml .= "        <City>". $address['city'] ."</City>\n";
    $xml .= "        <StateProvinceCode>". $address['region'] ."</StateProvinceCode>\n";
    $xml .= "        <CountryCode>". strtoupper($address['country']) ."</CountryCode>\n";
  }
  $xml .= "      </Address>\n";
  $xml .= "    </Shipper>\n";
  $xml .= "    <ShipTo>\n";
  $xml .= "      <Address>\n";
  if ($txn->address['shipping']->zip) {
    $xml .= "        <PostalCode>". $txn->address['shipping']->zip ."</PostalCode>\n";
    $xml .= "        <CountryCode>". strtoupper($txn->address['shipping']->country) ."</CountryCode>\n";
  }
  else {
    $xml .= "        <City>". $txn->address['shipping']->city ."</City>\n";
    $xml .= "        <StateProvinceCode>". $txn->address['shipping']->state ."</StateProvinceCode>\n";
    $xml .= "        <CountryCode>". $txn->address['shipping']->country ."</CountryCode>\n";
  }
  //$xml .= "        <ResidentialAddressIndicator></ResidentialAddressIndicator>\n"; // TODO:  true if exists, false if doesn't exist
  $xml .= "      </Address>\n";
  $xml .= "    </ShipTo>\n";
  // TODO:  Handle multiple packages -- currently we assume everything fits in
  //        the same package.  We could loop here and have up to 200 <Package>
  //        elements.
  $weight = 0;
  if (is_array($txn->items) && $txn->items != array()) {
    foreach ($txn->items as $item) {
      // Load product_weight into $item.
      shipping_nodeapi($item, 'load', NULL);
      if ($item->product_attributes['weight']) {
        $weight += $item->product_attributes['weight'] * $item->qty;
      }
    }
  }
  if ($weight == 0) {
    // This shouldn't happen, but if so assume minimum weight.
    $weight = 0.1;
  }
  $xml .= "    <Package>\n";
  $xml .= "      <PackagingType>\n";
  $xml .= "        <Code>". /*TODO, see Appendix C*/'02' ."</Code>\n";
  $xml .= "      </PackagingType>\n";
  $xml .= "      <PackageWeight>\n";
  $xml .= "        <UnitOfMeasurement>\n";
  $xml .= "          <Code>". variable_get('shipcalc_units', 'LBS') ."</Code>\n";
  $xml .= "        </UnitOfMeasurement>\n";
  $xml .= "        <Weight>". $weight ."</Weight>\n";
  $xml .= "      </PackageWeight>\n";
  $xml .= "    </Package>\n";
  $xml .= "  </Shipment>\n";
  $xml .= "</RatingServiceSelectionRequest>\n";
  return $xml;
}

/**
 * Internal helper function, not yet complete.  UPS returns an array of all
 * possible shipping methods.  We need to only return the ones that are 
 * configured for use with the current item.
 *
 * TODO: These names are valid for US, are different when shipping from 
 *       Europe or Canda.
 */
function _ups_valid_service_code($code, $txn, $testing = FALSE) {
  switch ($code) {
    case 1:
      $method = array('1DA' => t('Next Day Air'));
      break;
    case 2:
      $method = array('2DA' => t('2nd Day Air'));
      break;
    case 3:
      $method = array('GND' => t('Ground'));
      break;
    case 7:
      $method = array('XPR' => t('Worldwide Express'));
      break;
    case 8:
      $method = array('XDP' => t('Worldwide Expedited'));
      break;
    case 11:
      $method = array('STD' => t('Standard'));
      break;
    case 12:
      $method = array('3DS' => t('3 Day Select'));
      break;
    case 13:
      $method = array('1DP' => t('Next Day Air Saver'));
      break;
    case 14:
      $method = array('1DM' => t('Next Day Air, Early AM'));
      break;
    case 54:
      $method = array('XDM' => t('Worldwide Express Plus'));
      break;
    case 59:
      $method = array('2DM' => t('2nd Day Air, AM'));
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

