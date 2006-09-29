<?php
/* $Id: store.inc,v 1.17.2.5 2005/12/01 01:17:34 mathias Exp $ */

function store_transaction_overview_save($edit) {

  if (!is_numeric($edit['uid'])) {
    $edit['uid'] = db_result(db_query("SELECT uid FROM {users} WHERE name = '%s'", $edit['uid']));
  }
  return db_query("UPDATE {ec_transaction} SET uid = '%d', workflow = '%d', gross = '%f', payment_status = '%d', created = '%d', expires = '%d' WHERE txnid = '%d'", $edit['uid'], $edit['workflow'], $edit['gross'], $edit['payment_status'], $edit['created'], ($edit['expires'] != 0) ? $edit['expires'] : 0, $edit['txnid']);
}

function store_transaction_address_save($edit) {

  return db_query("UPDATE {ec_transaction} SET shipping_firstname = '%s', shipping_lastname = '%s', shipping_street1 = '%s', shipping_street2 = '%s', shipping_zip = '%s', shipping_city = '%s', shipping_state = '%s', shipping_country = '%d', billing_firstname = '%s', billing_lastname = '%s', billing_street1 = '%s', billing_street2 = '%s', billing_zip = '%s', billing_city = '%s', billing_state = '%s', billing_country = '%d' WHERE txnid = '%d'", $edit['shipping_firstname'], $edit['shipping_lastname'], $edit['shipping_street1'], $edit['shipping_street2'], $edit['shipping_zip'], $edit['shipping_city'], $edit['shipping_state'], $edit['shipping_country'], $edit['billing_firstname'], $edit['billing_lastname'], $edit['billing_street1'], $edit['billing_street2'], $edit['billing_zip'], $edit['billing_city'], $edit['billing_state'], $edit['billing_country'],  $edit['txnid']);
}

function store_transaction_item_save($edit) {

  foreach ($edit['item'] as $key => $value) {
    db_query("UPDATE {ec_transaction_product} SET qty = %d, price = %f, title = '%s' WHERE txnid = '%d' AND nid = '%d'", $value['qty'], $value['price'], $value['title'], $edit['txnid'], $key);
  }
}

function store_transaction_validate_overview(&$edit) {

  global $user;

  $errors = array();

  if (!is_numeric($edit['uid']) && db_result(db_query("SELECT COUNT(uid) FROM {users} WHERE name = '%s'", $edit['uid'])) == 1) {
    $edit['uid'] = db_result(db_query("SELECT uid FROM {users} WHERE name = '%s'", $edit['uid']));
  }

  /* Make sure we have a valid username or id */
  if ($edit['uid'] == variable_get("anonymous", "Anonymous")) {
    $edit['uid'] = 0;
  }
  elseif (!is_numeric($edit['uid']) && db_result(db_query("SELECT COUNT(uid) FROM {users} WHERE name = '%s'", $edit['uid'])) == 0) {
    $errors['uid'] = t('Invalid username');
  }

  if (!$edit['mail'] && $edit['uid'] != 0) {
    $edit['mail'] = $user->mail;
  }

  if ($msg = user_validate_mail($edit['mail'])) {
    $errors['mail'] = $msg;
  }

  $edit['changed'] = $edit['changed'] ? strtotime((int) $edit['changed']) : time();
  if ($edit['changed'] === FALSE || $edit['changed'] == -1) {
    $errors['changed'] = t('Invalid changed date. Please use the form "18 January 2005"');
  }

  if ($edit['expires']) {
    $edit['expires'] = strtotime($edit['expires']);
  }
  if ($edit['expires'] === FALSE || $edit['expires'] == -1) {
    $errors['expires'] = t('Invalid expiration date. Please use the form "18 January 2005"');
  }

  /* Remove the currency symbol at the beginning of the price if it exists */
  if (substr($edit['gross'], 0, 1) == variable_get('payment_symbol', '$')) {
    $edit['gross'] = substr($edit['gross'], count(variable_get('payment_symbol', '$')));
  }
  $edit['gross'] = str_replace(',', '', $edit['gross']);
  if (!is_numeric($edit['gross'])) {
    $errors['gross'] = t('Please enter a numeric value for the product price.');
  }

  foreach ($errors as $name => $message) {
    form_set_error($name, $message);
  }
  return count($errors) == 0;
}

function store_transaction_validate_addresses(&$edit, $validate = 'all') {

  global $user;

  $errors = array();

  if (variable_get('store_auth_cust', 1) == 0 && $user->uid == 0) {
    if ($edit['shippable'] && !($edit['shipping_firstname'] && $edit['shipping_lastname'] && $edit['shipping_street1'] && $edit['shipping_zip'] && $edit['shipping_city'] && $edit['shipping_state'])) {
      $shipping_error = t('Please fill out all shipping information fields.');
    }

    if (!($edit['billing_firstname'] && $edit['billing_lastname'] && $edit['billing_street1'] && $edit['billing_zip'] && $edit['billing_city'] && $edit['billing_state'])) {
      $billing_error = t('Please fill out all billing information fields.');
    }

    if (!$shipping_error && $billing_error) {
      $reset_bc = false;
      if ($validate == 'all') {
        foreach ($edit as $key => $value) {
          if (strstr($key, 'shipping_') !== false) {
            $name = substr($key, strlen('shipping_'));
            if (!$edit["billing_$name"]) {
              $edit["billing_$name"] = $value;
              $reset_bc = true;
            }
          }
        }
      }
      unset($billing_error);
    }

    if ($reset_bc) {
      $edit['billing_country'] = $edit['shipping_country'];
    }

    switch ($validate) {
      case 'shipping':
        if ($shipping_error) {
          $errors['shipping'] = $shipping_error;
        }
        break;

      case 'billing':
        if ($billing_error) {
          $errors['billing'] = $billing_error;
        }
        break;

      case 'all':
        if ($shipping_error) {
          $errors['shipping'] = $shipping_error;
        }

        if ($billing_error) {
          $errors['billing'] = $billing_error;
        }
        break;
    }
  }

  foreach ($errors as $name => $message) {
    form_set_error($name, $message);
  }
  return count($errors) == 0;
}

function store_transaction_validate_item(&$edit) {

  $errors = array();

  if ($edit['nids'] != '' && !preg_match("/^[0-9,\s]+$/", $edit['nids'])) {
    $errors['nids'] = t('Please enter only numeric values or commas in the product id field.');
  }

  if ($edit['item']) {
    foreach ($edit['item'] as $key => $value) {

      $edit['item'][$key]['nid'] = $key;
      foreach (explode("\n", $edit['item'][$key]['data']) as $d) {

        $d = explode(":", $d);
        if ($d[0]) {
          $arr[$d[0]] = trim($d[1]);
        }
      }
      $edit['item'][$key]['data'] = $arr;
      $edit['item'][$key] = array2object($edit['item'][$key]);

      if (strtotime($edit['item'][$key]->expires) != -1) {
        $edit['item'][$key]->expires = strtotime($edit['item'][$key]->expires);
      }
    }
  }

  foreach ($errors as $name => $message) {
    form_set_error($name, $message);
  }
  return count($errors) == 0;
}

function store_transaction_overview_form($edit) {

  if (is_numeric($edit->uid)) {
    $edit->uid = $edit->uid == 0 ? variable_get('anonymous', 'Anonymous') : db_result(db_query('SELECT name FROM {users} WHERE uid = %d', $edit->uid));
  }

  if ($edit->changed) {
    $edit->changed = format_date($edit->changed);
  }

  $fields = form_select(t('Transaction workflow'), 'workflow', $edit->workflow, store_transaction_workflow(), '');
  $fields .= form_textfield(t('Username'), 'uid', $edit->uid, 30, 60, t('Enter either an username or e-mail address below.'));
  $fields .= form_textfield(t('E-mail address'), 'mail', $edit->mail, 30, 64, null, null, true);
  $fields .= form_textfield(t('Total cost'), 'gross', $edit->gross, 10, 10, null, null, true);
  $payments = payment_build_status();
  if ($edit->payment_status == 2) {
    $help = t('Once a payment status is marked complete, it can\'t be changed');
  }
  $fields .= form_select(t('Payment status'), 'payment_status', $edit->payment_status, $payments, $help, $attr);
  $fields .= form_select(t('Payment method'), 'payment_method', $edit->payment_method, payment_get_methods(), '');
  if ($edit) {
    $fields .= form_textfield(t('Last modified'), 'changed', $edit->changed, 30, 30, '');
  }
  $fields .= form_textfield(t('Expires'), 'expires', ($edit->expires != 0) ? format_date($edit->expires) : '', 30, 30, t('For subscriptions, enter the date that the subscription expires.<br />Examples: "July 4, 2005", "+2 months", "+100 days", "next year", "tomorrow at 6:00pm"'));

  return form_group(t('Invoice overview'), $fields);
}

function store_transaction_addresses_form($edit, $display = null) {

  $fields = form_textfield(t('First name'), 'shipping_firstname', $edit->shipping_firstname, 32, 32, '');
  $fields .= form_textfield(t('Last name'), 'shipping_lastname', $edit->shipping_lastname, 32, 32, '');
  $fields .= form_textfield(t('Street1'), 'shipping_street1', $edit->shipping_street1, 60, 64, '');
  $fields .= form_textfield(t('Street2'), 'shipping_street2', $edit->shipping_street2, 60, 64, '');
  $fields .= form_textfield(t('City'), 'shipping_city', $edit->shipping_city, 32, 32, '');
  $fields .= form_textfield(t('State'), 'shipping_state', $edit->shipping_state, 32, 32, '');
  $fields .= form_textfield(t('Zip'), 'shipping_zip', $edit->shipping_zip, 10, 10, '');
  $fields .= form_select(t('Country'), 'shipping_country', ($edit->shipping_country ? $edit->shipping_country : variable_get('ec_country', '')), address_build_countries(), '');
  $shipping = form_group(t('Shipping address'), $fields);

  $fields = form_textfield(t('First name'), 'billing_firstname', $edit->billing_firstname, 32, 32, '');
  $fields .= form_textfield(t('Last name'), 'billing_lastname', $edit->billing_lastname, 32, 32, '');
  $fields .= form_textfield(t('Street1'), 'billing_street1', $edit->billing_street1, 60, 64, '');
  $fields .= form_textfield(t('Street2'), 'billing_street2', $edit->billing_street2, 60, 64, '');
  $fields .= form_textfield(t('City'), 'billing_city', $edit->billing_city, 32, 32, '');
  $fields .= form_textfield(t('State'), 'billing_state', $edit->billing_state, 32, 32, '');
  $fields .= form_textfield(t('Zip'), 'billing_zip', $edit->billing_zip, 10, 10, '');
  $fields .= form_select(t('Country'), 'billing_country', ($edit->billing_country ? $edit->billing_country : variable_get('ec_country', '')), address_build_countries(), '');
  $billing = form_group(t('Billing address'), $fields);

  switch ($display) {
    case 'shipping':
      return $shipping;
      break;

    case 'billing':
      return $billing;
      break;

    default:
      return $shipping . $billing;
  }
}

function store_transaction_item_form($edit) {

  $form = null;
  if (is_array($edit->item)) {
    foreach ($edit->item as $p) {

      $fields = form_textfield(t('Title'), 'item]['.$p->nid.'][title', $p->title, 30, 128, '');
      $fields .= form_textfield(t('Quantity'), 'item]['.$p->nid.'][qty', $p->qty, 15, 15, '');
      $fields .= form_textfield(t('Price'), 'item]['.$p->nid.'][price', $p->price, 15, 15, '');
      $fields .= form_hidden('item]['.$p->nid.'][data', $p->data);

      $interval = drupal_map_assoc(range(0, 31));
      $interval[0] = '--';
      $unit = array(0 => t('-please choose-'), 'D' => t('Day(s)'), 'W' => t('Week(s)'), 'M' => t('Month(s)'), 'Y' => t('Year(s)'));

      if ($p->expires) {
        $p->expires = format_date($p->expires, 'custom', 'Y-m-d H:i O');
      }

      if ($p->is_recurring) {
        $group = form_select(t('Interval'), 'item]['.$p->nid.'][price_interval', $p->price_interval, $interval);
        $group .= form_select(t('Unit'), 'item]['.$p->nid.'][price_unit', $p->price_unit, $unit);
        $interval[0] = 0;
        $interval[-1] = t('unlimited');
        $group .= form_select(t('Number of payments cycles'), 'item]['.$p->nid.'][price_cycle', $p->price_cycle, $interval, t('Select the number of recurring payment cycles this product has.'));
        $group .= form_textfield(t('expires'), 'item]['.$p->nid.'][expires', $p->expires, 21, 25, t('The date the product will expire, or if using recurring payments, the date of the next billing cycle unless all payment cycles have ended.'));
        $fields .= form_group(t('Billing cycle length'), $group);
      }

      $form .= form_group(t('Item #'). $p->nid.', '. $p->title, $fields);
    }
  }
  else {
    $fields = form_textfield(t('Product IDs'), 'nids', $edit->nids, 30, 128, t('Enter a comma separated list of product ids to add to this transaction. Here is a <a href="%product_quicklist" onclick="window.open(this.href, \'%product_quicklist\', \'width=480,height=480,scrollbars=yes,status=yes\'); return false">list of all products</a>.', array('%product_quicklist' => url('admin/store/products/quicklist'))));
    $form .= form_group(t('Products'), $fields);
  }

  return $form;
}

/**
 * Format an address with carriage returns or HTML line breaks.
 *
 * TODO:  The address object should be structured like the following:
 *   $address['shipping']->firstname
 *   insteaed of:
 *   $address->shipping_firstname.
 *
 * @param $data
 *   Object containing users address information.
 * @param $type
 *   Is this a shipping or billing address.
 * @param $break
 *   Should we break using '\n' (text) or '<br />' (html)
 */
function store_format_address($data, $type = 'shipping', $break = 'text') {
  if ($break != 'text') {
    foreach ($data as $key => $value) {
      if (is_scalar($value)) {
        $data->$key = check_plain($value);
      }
    }
  }
  $break = ($break == 'text') ? "\n" : '<br />';
  if ($type != 'shipping' && $type != 'billing') return false;

  $firstname  = $type. '_firstname';
  $lastname   = $type. '_lastname';
  $street1    = $type. '_street1';
  $street2    = $type. '_street2';
  $city       = $type. '_city';
  $state      = $type. '_state';
  $zip        = $type. '_zip';
  $country    = $type. '_country';
  $phone      = $type. '_phone';

  $address = NULL;
  if ($data->$firstname && $data->$lastname) {
    $address .= ucfirst($data->$firstname). ' '. ucfirst($data->$lastname). $break;
    $address .= ($data->$street2) ? $data->$street1 . $break . $data->$street2. $break : $data->$street1. $break;
    $address .= ucfirst($data->$city). ", ". strtoupper($data->$state). " ". $data->$zip. $break;
    $address .= address_get_country($data->$country). $break;
    $address .= $data->$phone. $break;
  }

  return $address;
}
?>
