<?php
// $Id: sitewide.inc,v 1.1.2.2 2006/11/06 00:01:24 sime Exp $

/**
 * @file
 * sitewide.inc
 *
 * This is a module that provides a charge to flexicharge module.
 *
 * It is called "sitewide" because charges are applied to everyone
 * at the end of the cart.
 *
 */


function sitewide_charge_methods() {
  $methods = array();

  $methods['sitewide'] = array(
    '#description' => t('Site-wide Charges.')
  );
  $methods['sitewide']['general'] = array(
    '#title' => t('Site-wide charge.'),
  );

  return $methods;
}

/**
 * _flexicharge_form_elements hook.
 *
 * This function is called so that the charge provider can add
 * required fields to the new charge form.
 *
 */
function sitewide_flexicharge_form_elements(&$form, $op, $method) {

  $elements = array();

  switch ($method) {
    case 'general':
      /*
       * Here is an example form element to add.
       * You can also kill default fields by referring to the references $form element
       *
      $elements['something'] = array(
        '#type' => 'textfield',
        '#required' => true,
        '#title' => t('some description'),
        '#description' => t('This is for...'),
        '#size' => 8,
      );
      */
      break;
  }

  return $elements;
}

/**
 * implementation of hook_flexicharge_form_validate.
 *
 * This function is called so that the charge provider can
 * validate the fields of a new charge on submission. The charge's
 * own form elements can be found at $form['module']['elements'].
 *
 */
function sitewide_flexicharge_form_validate($form_id, $form) {

  switch ($method) {
    case 'general':
  }

  return $form;
}

/**
 * Implementation of hook_flexicharge_calculate.
 *
 * We are just catching the 'review' operation of the checkoutapi.
 * This is where most misc calculations will want to work. On the
 * review screen.
 *
 */
function sitewide_flexicharge_review(&$txn, $charge) {

  switch ($charge->method) {
    case 'general':
         $misc = array(
           'type' => 'fl_'. $charge->chid,
           'description' => $charge->display,
           'operator' => $charge->operator,
           'rate' => $charge->rate,
           'subtotal_before' => $charge->subtotal_before,
           'subtotal_after' => $charge->subtotal_before,
           'already_added' => $charge->already_added,
           'weight' => $charge->position,
           'method' => $charge->method,
           'callback' => 'general_sitewide_flexicharge_calculate',
         );
      break;
  }
  return $misc;
}

/**
 * 'General' charge calculation
 *
 * FLEXICHARGE_CHARGE                 +/-
 * FLEXICHARGE_CHARGE_PCT_SUBTOTAL    % of current total
 * FLEXICHARGE_CHARGE_PCT_ITEMTOTAL   % of item total
 *
 */
function general_sitewide_flexicharge_calculate(&$txn, $misc, $total) {

  $this_total = 0;
  // Do the pct if needed
  if (($misc->operator != FLEXICHARGE_CHARGE) && $misc->rate) {
    $factor = 1;
    if($misc->rate < 0 ) {$factor = -1;}
    $pct = ($misc->rate * $factor) / 100;
  }

  switch ($misc->operator) {
    case FLEXICHARGE_CHARGE:
      return $misc->rate;
    case FLEXICHARGE_CHARGE_PCT_SUBTOTAL:
      return ($total * $pct * $factor);
    case FLEXICHARGE_CHARGE_PCT_ITEMTOTAL:
      $value = 0;
      foreach ((array)$txn->items as $item) {
        if (product_has_quantity($item)) {
          $value += ($item->price * $item->qty);
        }
        else {
          $value += $item->price;
        }
      }
      return ($value * $pct * $factor);
   }
}


/**
 * Implementation of hook_flexicharge_checkoutapi.
 *
 * Perhaps most methods won't catch this, because we break have broken out the review
 * $op into hook_flexicharge_calculate
 *
 */
function sitewide_flexicharge_checkoutapi(&$txn, $op, $arg3 = NULL, $arg4 = NULL) {
  switch ($op) {
    case 'form':
      break;
    case 'validate':
      break;
    case 'save':
      break;
  }
}