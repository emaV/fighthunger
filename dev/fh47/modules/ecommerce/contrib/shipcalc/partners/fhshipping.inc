<?php
// $Id: fhshipping.inc,v 1.0 2006/11/19 jose Exp $

/**
 * @file
 * Shipcalc plugin for Fighthunger.org, http://www.fighthunger.org
 *
 * Created by Jose A. Reyero, http://www.reyero.net
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
  $methods['fhshipping']['percountry'] = array(
    '#title' => t('Per country company selection'),
  );
  $methods['fhshipping']['testing'] = array(
    '#title' => t('Just for testing'),
  );
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
  // DEBUG
  // drupal_set_message("Shipping parameters: weight=$weight country=$country");

  // Select company, zone  depending on country
  $method = db_fetch_object(db_query("SELECT * FROM {fh_country_shipping} WHERE countrycode = '%s'", $country));
  if (!$method->company || !$method->zone) {
  	drupal_set_message(t('No shipping method available for this country. Please contact site administrator.'), 'error');
    // No localized message
    watchdog('shipping', "No shipping method for countrycode=$country", WATCHDOG_WARNING);
    drupal_goto('cart/checkout');
  }
  
  // Get price for weight
  $price = db_result(db_query_range("SELECT price FROM {fh_shipping_price} WHERE company = '%s' AND zone = '%s' AND weight >= %d ORDER BY weight", $method->company, $method->zone, $weight, 0, 1));
  if (!$price) {
  	// Weight is out of range
    $weight = db_result(db_query("SELECT MAX(weight) FROM {fh_shipping_price} WHERE company = '%s' AND zone = '%s'", $method->company, $method->zone, $weight, 0, 1));
    // No localized message
    watchdog('shipping', "Shipping weight out of range: weight=$weight, country=$country, company=$method->company", WATCHDOG_WARNING);
    drupal_set_message(t('Maximum shipping weight allowed is %weight %unit. Please review your shopping cart.', array('%weight' => $weight, '%unit' => (variable_get('shipcalc_units', 'LBS') ? t('pounds') : t('kilograms')))), 'error');
    drupal_goto('cart/view');
  }

  // Prices are stored in Euros, but USD are used instead, so we apply some exchange rate
  $price = floatval($price) * floatval(variable_get('fhcommerce_euro2usd', 1));
  
  // Debug message
  watchdog('shipping', "Successful calculation: country=$country ,price=$price USD, Company=$method->company, zone=$method->zone");  
  
  $rates[] = array(
        '#service' => 'fhshipping',
        '#key' => 'percountry',
        '#cost' => $price,
        '#currency' => FHSHIPPING_CURRENCY,
        '#method' => t('Pricing based on weight and destination')
  );
  /* This was for testing
  $rates[] = array(
        '#service' => 'fhshipping',
        '#key' => 'testing',
        '#cost' => 2 * $price,
        '#currency' => FHSHIPPING_CURRENCY,
        '#method' => t('Just for testing')
  );
  */
  return $rates;
}

?>