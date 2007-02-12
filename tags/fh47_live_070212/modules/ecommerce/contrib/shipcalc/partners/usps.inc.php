<?php
// $Id: usps.inc,v 1.1.2.5 2006/11/06 04:27:16 nedjo Exp $

/**
 * @file
 * Functions to communicate with USPS API.
 *
 * Derived from the USPS document titled:
 *    Rates Calculators
 *    USPS Web Tools(TM)
 *    Application Programming Interface
 *    User's Guide
 *    Document Version 1.0 (11/8/04)
 *
 * Additional information:
 *  http://www.usps.com/webtools/
 */

/**
 * Shipcalc _shipping_methods hook.
 *
 * Define the USPS shipping methods.
 */
function usps_shipping_methods($type = 'domestic') {
  // TODO: Add descriptions of various shipping methods.
  $methods = array();

  $methods['usps'] = array(
    '#title' => t('USPS'),
    '#description' => t('United States Postal Service.'),
    '#product_attributes' => array('weight', 'length', 'width', 'depth')
  );

  // We substitute underscores for spaces to ensure proper handling of the
  // values in e.g. form element names. We need to filter these out
  // when comparing to values used by USPS.
  switch ($type) {
    case 'domestic':
    default:
      $methods['usps']['Express_Mail_to_PO_Addressee'] = array(
        '#title' => t('Express Mail to PO Addressee'),
      );
      $methods['usps']['First-Class_Mail'] = array(
        '#title' => t('First-Class Mail'),
      );
      $methods['usps']['Priority_Mail'] = array(
        '#title' => t('Priority Mail'),
      );
      $methods['usps']['Parcel_Post'] = array(
        '#title' => t('Parcel Post'),
      );
      $methods['usps']['Bound_Printed_Matter'] = array(
        '#title' => t('Bound Printed Matter'),
      );
      $methods['usps']['Media_Mail'] = array(
        '#title' => t('Media Mail'),
      );
      $methods['usps']['Library_Mail'] = array(
        '#title' => t('Library Mail')
      );
      break;
    case 'international':
      // Note: these method names are not confirmed and will need
      // revisiting to ensure they are correct. USPS documentation
      // isn't the best. Keys given in documentation sometimes differ
      // from what is actually returned by a given web service.
      $methods['usps']['GXG_Document'] = array(
        '#title' => t('Global Express Guaranteed Document Service'),
      );
      $methods['usps']['GXG_Non-Document'] = array(
        '#title' => t('Global Express Guaranteed Non-Document Service'),
      );
      $methods['usps']['Priority_Lg'] = array(
        '#title' => t('Global Priority Mail, Flat-rate Large Envelope'),
      );
      $methods['usps']['Priority_Sm'] = array(
        '#title' => t('Global Priority Mail, Flat-rate Small Envelope'),
      );
      $methods['usps']['Priority_Var'] = array(
        '#title' => t('Global Priority Mail, Variable Rate'),
      );
      $methods['usps']['Express'] = array(
        '#title' => t('Global Express Mail'),
      );
      $methods['usps']['Airmail_Letter'] = array(
        '#title' => t('Airmail Letter Post'),
      );
      $methods['usps']['Airmail_Parcel'] = array(
        '#title' => t('Airmail Parcel Post'),
      );
      $methods['usps']['Surface_Letter'] = array(
        '#title' => t('Economy Mail, Letter Post')
      );
      $methods['usps']['Surface_Parcel'] = array(
        '#title' => t('Economy Mail, Parcel Post')
      );
  }

  return $methods;
}

/**
 * Shipcalc _settings_form hook.
 *
 * Create a form for USPS-specific configuration.
 */
function usps_settings_form(&$form) {
  $form['usps'] = array(
    '#type' => 'fieldset',
    '#title' => t('USPS settings')
  );
  $form['usps']['usps_userid'] = array(
    '#type' => 'textfield',
    '#title' => t('USPS User ID'),
    '#description' => t('Your unique USPS User ID is provided when you %register for the USPS Web Tools.', array('%register' => l(t('register'), url('https://secure.shippingapis.com/Registration/')))),
    '#default_value' => variable_get('shipcalc_usps_userid', ''),
    '#required' => TRUE
  );
  $form['usps']['usps_password'] = array(
    '#type' => 'password',
    '#title' => t('USPS Password'),
    '#description' => t('Your unique USPS password is provided when you %register for the USPS Web Tools.', array('%register' => l(t('register'), url('https://secure.shippingapis.com/Registration/')))),
    '#default_value' => variable_get('shipcalc_usps_password', ''),
    '#required' => FALSE // Not required during testing.
  );

  $form['usps']['usps_url'] = array(
    '#type' => 'textfield',
    '#title' => t('USPS Server URL'),
    '#description' => t('Enter the fully qualified URL and of the USPS shipping rate server, including the API DLL, as provided by USPS.  For testing, you generally use <em>http://testing.shippingapis.com/ShippingAPITest.dll</em> or <em>https://secure.shippingapis.com/ShippingAPITest.dll</em>.  For production, you generally use <em>http://production.shippingapis.com/shippingapi.dll</em>.'),
    '#default_value' => (variable_get('shipcalc_usps_url', '') ? variable_get('shipcalc_usps_url', '') : 'http://testing.shippingapis.com/ShippingAPITest.dll'),
    '#required' => TRUE
  );

  // TODO: Testing to help admin set up site.  Not fully implemented yet.
  $form['usps']['test'] = array(
    '#type' => 'fieldset',
    '#title' => t('Testing'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE
  );
  $form['usps']['test']['usps_test_url'] = array(
    '#type' => 'textfield',
    '#title' => t('USPS Test Server URL'),
    '#description' => t('USPS provides a service to test your site configuration prior to launch.  Enter the fully qualified URL of the USPS shipping rate testing server.  Clicking <em>Test configuration</em> below will use your User ID and (optional for testing) Password to test a transaction against the USPS Test Server URL.'),
    '#default_value' => (variable_get('shipcalc_usps_test_url', '') ? variable_get('shipcalc_usps_test_url', '') : 'http://testing.shippingapis.com/ShippingAPITest.dll'),
    '#required' => TRUE
  );

  $form['usps']['test']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Test usps configuration')
  );

  return $form;
}

/**
 * Shipcalc _settings_form_submit hook.
 *
 * Save data from our USPS-specific configuration form.
 */
function usps_settings_form_submit(&$form) {
  global $form_values;
  $op = $_POST['op'];

  if ($form_values['shipping_partner'] == 'usps') {
    variable_set('shipcalc_usps_userid', $form_values['usps_userid']);
    variable_set('shipcalc_usps_password', $form_values['usps_password']);
    variable_set('shipcalc_usps_url', $form_values['usps_url']);
    variable_set('shipcalc_usps_test_url', $form_values['usps_test_url']);
  }
  if ($op == t('Test usps configuration')) {
    $txn->address['shipping']->zip = 20008;
    $txn->address['shipping']->country = 'us';
    // 
    $request = '<RateV2Request USERID="'. variable_get('shipcalc_usps_userid', '') .'" PASSWORD="'. variable_get('shipcalc_usps_password', '') .'"><Package ID="0"><Service>All</Service><ZipOrigination>10022</ZipOrigination><ZipDestination>20008</ZipDestination><Pounds>10</Pounds><Ounces>5</Ounces><Size>LARGE</Size><Machinable>TRUE</Machinable></Package></RateV2Request>';
    $rates = usps_get_rates($txn, $form_values['usps_test_url'], TRUE, $request);
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
function usps_product_attributes($form) {
  $fields = array();
  $fields['weight'] = array(
    '#type' => 'textfield',
    '#title' => t('Product weight'),
    '#description' => t('The weight of the product (in %unit)', array('%unit'=> (variable_get('shipcalc_units', 'LBS')) ? t('pounds') : t('kilograms'))),
    '#default_value' => $form['#node']->product_attributes['weight']
  );
  $fields['length'] = array(
    '#type' => 'textfield',
    '#title' => t('Product length'),
    '#description' => t('The length of the product (in inches)'),
    '#default_value' => $form['#node']->product_attributes['length']
  );
  $fields['width'] = array(
    '#type' => 'textfield',
    '#title' => t('Product width'),
    '#description' => t('The width of the product (in inches)'),
    '#default_value' => $form['#node']->product_attributes['width']
  );
  $fields['depth'] = array(
    '#type' => 'textfield',
    '#title' => t('Product depth'),
    '#description' => t('The depth of the product (in inches)'),
    '#default_value' => $form['#node']->product_attributes['depth']
  );
  return $fields;
}

/**
 * Shipcalc _get_rates_form hook.
 */
function usps_get_rates($txn, $url = 'DEFAULT', $testing = FALSE, $request = NULL) {
  $rates = array();
  if ($url == 'DEFAULT') {
    $url = variable_get('shipcalc_usps_url', 'http://testing.shippingapis.com/ShippingAPITest.dll');
  }

  $url .= '?API=RateV2&XML='. urlencode(($request ? $request : usps_RateV2Request($txn)));

  $result = drupal_http_request($url, array('Content-type' => 'text/xml'), 'GET');

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

  // TODO: This assumes we requested everything as one package, and will need
  // to be changed if/when we issue separate requests by item being purchased.
  $xml = $result->data;

  if (!strpos($xml, '<Error>') === FALSE) { // failed request 
    $error = _parse_xml($xml, '<Error>');
    $error_code = _parse_xml($error, '<Number>');
    $error_description = _parse_xml($error, '<Description>');
    drupal_set_message(t('%description (error %code)', array('%code' => $error_code, '%description' => $error_description)), 'error');
    return -1; // negative charges indicates an error
  }
  else { // success, build form
    // Note: this line failed because our simple XML parsing doesn't handle
    // attributes (the 'ID="0"').
    // This isn't needed here, but would be if we were iterating through
    // a list of Packages.
    // $xml = _parse_xml(_parse_xml($xml, '<RateV2Response>'), '<Package ID="0">');
    $loop = TRUE;
    $options = array();
    while ($loop == TRUE) {
      if (strpos($xml, '<Postage>')) {
        $rate = _parse_xml($xml, '<Postage>');
        // See if this is a supported shipping method.
        $service = _parse_xml($rate, '<MailService>');
        if ($method = _usps_valid_service_method($service, $txn, $testing)) {
          $total = _parse_xml($rate, '<Rate>');

          $rates[] = array(
            '#service' => 'usps',
            '#key' => key($method),
            '#cost' => $total,
            '#currency' => NULL,
            '#method' => current($method)
          );
        }
        $xml = substr($xml, strpos($xml, '</Postage>') + 1);
      }
      else {
        $loop = FALSE;
      }
    }
  }

  return $rates;
}

/**
 * Build the XML RateV2Request used to make a request from the
 * USPS shipping server.
 */
function usps_RateV2Request($txn) {
  // TODO:  Handle multiple packages -- currently we assume everything fits in
  //        the same package.

  // Set default weight.
  $weight = 0;
  if (is_array($txn->items) && $txn->items != array()) {
    foreach ($txn->items as $item) {
      // Load product weight into $item.
      shipping_nodeapi($item, 'load', NULL);
      if ($item->product_attributes['weight']) {
        $weight += $item->product_attributes['weight'] * $item->qty;
      }
      // We use the length, width, and depth of all items to estimate overall
      // dimensions. We assume the overall package will have the hightest length
      // and height and the combined depth.
      if ($item->product_attributes['length'] && $item->product_attributes['width'] && $item->product_attributes['depth']) {
        // For the first item, we take its dimensions.
        if (!isset($dimensions)) {
          $dimensions = array('length' => $item->product_attributes['length'], 'width' => $item->product_attributes['width'], 'depth' => $item->product_attributes['depth']);
        }
        else {
          // If subsequent items have a greater length or width, use those.
          foreach (array('length', 'width') as $attribute) {
            if ($item->product_attributes[$attribute] > $dimensions[$attribute]) {
              $dimensions[$attribute] = $item->product_attributes[$attribute];
            }
          }
          // The depth is cumulative--we add together all items' depths.
          $dimensions['depth'] += $item->product_attributes['depth'] * $item->qty;
        }
      }
    }
  }
  // If necessary, convert weight from kgs to lbs.
  if (variable_get('shipcalc_units', 'LBS') == 'KGS') {
    $weight = $weight * 2.20462;
  }
  // Determine the length plus girth (the measurement used to determine size categories by USPS).
  $length_plus_girth = $dimensions['length'] + ($dimensions['width'] * 2) + ($dimensions['depth'] * 2);
  // Assign the appropriate package size.
  if (($weight < 15) && ($length_plus_girth <= 84)) {
    $size = 'REGULAR';
  }
  elseif (($weight < 15) && ($length_plus_girth <= 118)) {
    $size = 'LARGE';
  }
  elseif ($length_plus_girth <= 130) {
    $size = 'OVERSIZE';
  }
  else {
    $size = 'OVERSIZE';
    drupal_set_message('Package length plus girth exceeds 130 inches. The package may be too large to be mailed by USPS.', 'error');
  }
  // Assign appropriate container sizes.
  // The container is used only for Express and Priority. If we include it, 
  // our search for ALL will return only one method--that valid for the specified
  // container. So for now we don't support containers and their associated
  // shipping methods. That is, we support Express and Priority, but not
  // e.g. 'Express Mail Flat Rate Envelope (12.5" x 9.5")'.

  /**
   * Not used (yet).
  // Assume 1 inch as maximum depth for "flat" container.
  if (($dimensions['length'] <= 12.5) && ($dimensions['width'] <= 9.5) && ($dimensions['depth'] <= 1)) {
    $container_dimensions = '12.5" x 9.5"';
  }
  else if (($dimensions['length'] <= 14) && ($dimensions['width'] <= 12) && ($dimensions['depth'] <= 3.5)) {
    $container_dimensions = '14" x 12" x 3.5"';
  }
  else if (($dimensions['length'] <= 11.25) && ($dimensions['width'] <= 8.75) && ($dimensions['depth'] <= 6)) {
    $container_dimensions = '11.25" x 8.75" x 6"';
  }
  */
  // Set a minimum weight of 0.1 lbs.
  $weight = ($weight < 0.1 ? 0.1 : $weight);
  // Convert from pounds into pounds and ounces.
  $pounds = floor ($weight);
  $ounces = round (16 * ($weight - floor ($weight)));
  // Determine by weight if package is machinable.
  // TODO: this determination is borrowed from oscommerce. Needs
  // to be reviewed.
  $machinable = ($pounds > 35 || ($pounds == 0 && $ounces < 6)) ? 'False' : 'True';
  $address = shipping_default_shipfrom();
  // If both origination and destination are in US, use domestic service.
  if (($address['country'] == 'us') && ($txn->address['shipping']->country == 'us')) {
    // We encode this on one line as it will be sent via URL-encoded GET.
    $xml = 
    '<RateV2Request USERID="'. variable_get('shipcalc_usps_userid', '') .'" PASSWORD="'. variable_get('shipcalc_usps_password', '') .'">'.
    '<Package ID="0">'.
    '<Service>ALL</Service>' .
    '<ZipOrigination>'. $address['code'] . '</ZipOrigination>'.
    '<ZipDestination>'. $txn->address['shipping']->zip . '</ZipDestination>'.
    '<Pounds>'. $pounds .'</Pounds>'.
    '<Ounces>'. $ounces .'</Ounces>'.
    '<Container></Container>'.
    '<Size>'. $size .'</Size>'.
    '<Machinable>'. $machinable .'</Machinable>'.
    '</Package>'.
    '</RateV2Request>';
  }
  // TODO: international
  else {
  }
  return $xml;
}

/**
 * Internal helper function.
 */
function _usps_valid_service_method($method, $txn, $testing) {
  $methods = usps_shipping_methods();
  // We use keys with underscores in the place of spaces.
  $method = str_replace(' ', '_', $method);

  // If we're testing, we bypass the test to see if this method is valid for a particular product.
  if ($testing) {
    if (in_array($method, array_keys($methods['usps']))) {
      return array($method => $methods['usps'][$method]['#title']);
    }
    return FALSE;
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
          if (in_array($method, $supported)) {
            return array($method => $methods['usps'][$method]['#title']);
          }
        }
      }
    }
  }
  return NULL;
}
