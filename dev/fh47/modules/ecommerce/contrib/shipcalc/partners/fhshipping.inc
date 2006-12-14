<?php
// $Id: fhshipping.inc,v 1.0 2006/11/19 jose Exp $

/**
 * @file
 * Shipcalc plugin for Fighthunger.org, http://www.fighthunger.org
 *
 * This plug-in calculates shipping price for available companies 
 * depending on country, weight
 * 
 * Jose A. Reyero, 2006
 */

define('FHSHIPPING_CURRENCY', 'USD');
/**
 * Shipcalc _shipping_methods hook.
 *
 * Define the UPS shipping methods.
 */
function fhshipping_shipping_methods($type = 'domestic') {

  $methods = array();

  $methods['fhshipping'] = array(
    '#title' => t('Fighthunger custom'),
    '#description' => t('FH Custom shipping.')
  );
  $methods['fhshipping']['SDA'] = array(
    '#title' => t('Courier: SDA'),
  );
  $methods['fhshipping']['DHL'] = array(
    '#title' => t('Courier: DHL'),
  );
  $methods['fhshipping']['Posteitaliane'] = array(
    '#title' => t('Courier: Posteitaliane'),
  );
  $methods['fhshipping']['EasyMail'] = array(
    '#title' => t('Postal Service: Easy Mail'),
  );     
  /*
  $methods['fhshipping']['easymail'] = array(
    '#title' =>  t('Easy mail.')
  );
  */
  return $methods;
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
function fhshipping_product_attributes($form) {
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
 * Calculate rates from stored data
 *  - Company, zone selected from country
 *  - Final price depending on weight
 */
function fhshipping_get_rates($txn, $url = 'DEFAULT', $testing = FALSE) {
    // First, calculate total weight
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
  // Get country from transaction
  $country = $txn->address['shipping']->country;

  // Select companies, zone  depending on country
  $result = db_query("SELECT * FROM {fh_country_shipping} WHERE countrycode = '%s'", $country);
  $shipping = array();
  $maxweight = 0;
  while ($method = db_fetch_object($result)) {
    if($method->company && $method->zone) {
      // Prices are stored in Euros, but USD are used instead, so we apply some exchange rate
      $price = db_result(db_query_range("SELECT price FROM {fh_shipping_price} WHERE company = '%s' AND zone = '%s' AND weight >= %s ORDER BY weight", $method->company, $method->zone, $weight, 0, 1));
      if($price) {
        $method->price = fhshipping_price_currency($price);
        $shipping[$method->company] = $method;
      } else {
        $maxweight = max($maxweight, db_result(db_query("SELECT MAX(weight) FROM {fh_shipping_price} WHERE company = '%s' AND zone = '%s'", $method->company, $method->zone, 0, 1)));
      }
    }
  }
  // Did we find company, zone ?
  if (!count($shipping)) {
    if($maxweight) {
      // No localized message
      watchdog('shipping', "Shipping weight out of range: weight=$weight, country=$country", WATCHDOG_WARNING);
      drupal_set_message(t('Total weight is %weight %unit, but maximum shipping weight allowed is %maxweight %unit. Please review your shopping cart.', array('%weight' => $weight, '%maxweight' => $maxweight, '%unit' => (variable_get('shipcalc_units', 'LBS') ? t('pounds') : t('kilograms')))), 'error');
      drupal_goto('cart/view');
    } else {
      // No localized message
      drupal_set_message(t('No shipping method available for this country. Please contact site administrator.'), 'error');
      watchdog('shipping', "No shipping method for countrycode=$country", WATCHDOG_WARNING);
      drupal_goto('cart/checkout');
    }
  }
  // Log data
  /*
  drupal_set_message(t("Shipping parameters: weight=%weight %unit country=%country", array(
    '%weight' => $weight,
    '%unit' => (variable_get('shipcalc_units', 'LBS') ? t('pounds') : t('kilograms')),
    '%country' => module_invoke('fh', 'get_country_name', $country)
  )));
  */
  // Build array with different options
  
  $description = fhshipping_shipping_methods();
  foreach ($shipping as $method) {
    $rates[] = array(
        '#service' => 'fhshipping',
        '#key' => $method->company,
        '#cost' => $method->price,
        '#currency' => FHSHIPPING_CURRENCY,
        '#method' => $description['fhshipping'][$method->company]['#title'],
    );
  }
  $rates[] = array(
      '#service' => 'fhshipping',
      '#key' => 'WFP direct',
      '#cost' => 0,
      '#currency' => FHSHIPPING_CURRENCY,
      '#method' => t("Pick up items up from WFP HQ (St Martins 4S10) - <a href='/shop/WFPhqpickup' target='nwin'>more info</a>"),
  );

  return $rates;
}

/**
 * Convert prices to USD and round to 2 decimal digits
 */
function fhshipping_price_currency($price) {
  return round(floatval($price) * floatval(variable_get('fhcommerce_euro2usd', 1)), 2);
}
?>
