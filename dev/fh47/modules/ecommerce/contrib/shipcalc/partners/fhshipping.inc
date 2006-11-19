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
  drupal_set_message("Shipping parameters: weight=$weight country=$country");

  // Company depending on country
  $method = db_fetch_object(db_query("SELECT * FROM {fh_country_shipping} WHERE countrycode = '%s'", $country));
  // DEBUG
  drupal_set_message("Company=$method->company zone=$method->zone");
  // Get price for weight
  $result = db_query_range("SELECT * FROM {fh_shipping_price} WHERE company = '%s' AND zone = '%s' AND weight >= %d ORDER BY weight", $method->company, $method->zone, $weight, 0, 1);
  $price = db_fetch_object($result);
  $rates[] = array(
        '#service' => 'fhshipping',
        '#key' => 'percountry',
        '#cost' => $price->price,
        '#currency' => FHSHIPPING_CURRENCY,
        '#method' => t('Pricing based on weight and destination')
  );
  $rates[] = array(
        '#service' => 'fhshipping',
        '#key' => 'testing',
        '#cost' => 2 * $price->price,
        '#currency' => FHSHIPPING_CURRENCY,
        '#method' => t('Just for testing')
  );
  return $rates;
}

?>