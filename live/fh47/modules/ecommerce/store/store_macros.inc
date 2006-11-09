<?php
/* $Id: store_macros.inc,v 1.4 2006/03/12 12:24:21 gordon Exp $ */

/* You need to have the macrotags module for these to work. */

function mt_cart_link_help($section = 'short-tip') {
  switch ($section) {
    case 'short-tip':
    case 'long-tip':
      $output = t('<pre>[cart_link|product id|link text(optional)|quantity(optional)]</pre>');
      $output .= t("<p>This tag allows you to create an 'add to cart link', where 'product id' is the unique ID of the product you wish the button link to be made for. 'link text' is the words you want to be linked and 'quantity' is the number of items to add to the cart when the button link is clicked.</p>");
      break;
  }
    
  return $output;
}

/**
 * Create hyperlinks
 */
function mt_cart_link($id, $text = 'add to cart', $qty = 1) {

  return  l($text, "cart/add/$id/$qty");
}

function mt_product_price_help($section = 'short-tip') {
  switch ($section) {
    case 'short-tip':
    case 'long-tip':
      $output = t('<pre>[product_price|product id|number of decimal places(optional)]</pre>');
      $output .= t("<p>This tag allows you to display the price of a product. If you don't want to display a value for the decimal places, enter 0 for the 'number of decimal places' value.</p>");
      break;
  }
    
  return $output;
}

/**
 * Get prices
 */
function mt_product_price($id, $num_decimal = 2) {

  $price = db_result(db_query("SELECT price FROM {ec_product} WHERE nid = '%d'", $id));
  
  if (is_numeric($num_decimal)) {
    $price = number_format($price, $num_decimal);
  }
  $price = variable_get('payment_symbol', '$'). $price;

  return $price;
}

?>
