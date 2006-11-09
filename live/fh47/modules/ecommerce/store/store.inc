<?php
/* $Id: store.inc,v 1.38.2.4 2006/10/12 12:49:06 sammys Exp $ */

function store_transaction_overview_save($edit) {

  if (!is_numeric($edit['uid'])) {
    $edit['uid'] = db_result(db_query("SELECT uid FROM {users} WHERE name = '%s'", $edit['uid']));
  }
  return db_query("UPDATE {ec_transaction} SET uid = '%d', workflow = '%d', payment_status = '%d', created = '%d', expires = '%d' WHERE txnid = '%d'", $edit['uid'], $edit['workflow'], $edit['payment_status'], $edit['created'], ($edit['expires'] != 0) ? $edit['expires'] : 0, $edit['txnid']);
}

/**
 * Saves all addresses in transaction using store_transaction_address_save
 * 
 * @param object $txn transaction with address array
 * @param bool $new toggles INSERT/UPDATE in store_transaction_address_save
 */
function store_transaction_addresses_save($txn) {
  $txn = (object)$txn; // just to be sure

  // If we where not passed a valid transaction and we can't save.
  if (!isset($txn->txnid) || !is_array($txn->address)) return FALSE;

  foreach ($txn->address as $type => $address) {
    if (is_array($address)) {
      $address = (object)$address;
    }
    $address->txnid = $txn->txnid;
    store_transaction_address_save($address, $type);
  }
}

/**
 * Saves an individual address to the passed transaction
 * 
 * @param object $address address object
 * @param string $type address type(corresponds to type field in database)
 */
function store_transaction_address_save($address, $type) {
  $address = (object)$address; // just to be sure
  if (db_result(db_query('SELECT COUNT(txnid) FROM {ec_transaction_address} WHERE txnid = %d AND type = \'%s\'', $address->txnid, $type))) {
    db_query('UPDATE {ec_transaction_address} ' .
      "SET firstname = '%s', lastname = '%s', street1 = '%s', street2 = '%s', " .
      "zip = '%s', city = '%s', state = '%s', country = '%s' " .
      "WHERE txnid = %d AND type = '%s'",
      $address->firstname, $address->lastname, $address->street1, $address->street2,
      $address->zip, $address->city, $address->state, $address->country, $address->txnid, $type);
  }
  else {
    db_query('INSERT INTO {ec_transaction_address} (firstname, lastname, street1, street2, zip, city, state, country, txnid, type) VALUES' .
      "('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, '%s')",
      $address->firstname, $address->lastname, $address->street1, $address->street2,
      $address->zip, $address->city, $address->state, $address->country, $address->txnid, $type);
  }
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

  foreach ($errors as $name => $message) {
    form_set_error($name, $message);
  }
  return count($errors) == 0;
}

/**
 * This function validates addressess on certain admin forms I think...
 * This hasn't been converted to the new address system
 * TODO Fix this
 */
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
      $edit['item'][$key] = (object)($edit['item'][$key]);

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
    $account = user_load(array('uid' => $edit->uid));
    $edit->uid = $edit->uid == 0 ? variable_get('anonymous', 'Anonymous') : $account->name;
  }

  if(!$edit->mail) {
    $edit->mail = $account->mail;
  }

  if ($edit->changed) {
    $edit->changed = format_date($edit->changed);
  }

  $form['overview'] = array(
    '#type' => 'fieldset',
    '#title' => t('Invoice overview'),
    );
  $form['overview']['workflow'] = array(
    '#type' => 'select',
    '#title' => t('Transaction workflow'),
    '#default_value' => $edit->workflow,
    '#options' => store_transaction_workflow(),
  );
  $form['overview']['uid'] = array(
    '#type' => 'textfield',
    '#title' => t('Username'),
    '#default_value' => $edit->uid,
    '#size' => 30,
    '#maxlength' => 60,
    '#description' => t('Enter either an username or e-mail address below.'),
  );
  $form['overview']['mail'] = array(
    '#type' => 'textfield',
    '#title' => t('E-mail address'),
    '#default_value' => $edit->mail,
    '#size' => 30,
    '#maxlength' => 64,
  );
  $form['overview']['gross'] = array(
    '#type' => 'item',
    '#title' => t('Total cost'),
    '#value' => payment_format($edit->gross),
  );
  $payments = payment_build_status();
  if ($edit->payment_status == 2) {
    $help = t('Once a payment status is marked complete, it can\'t be changed');
    $attr['disabled'] = 'true';
  }
  $form['overview']['payment_status'] = array(
    '#type' => 'select',
    '#title' => t('Payment status'),
    '#default_value' => $edit->payment_status,
    '#options' => $payments,
    '#description' => $help,
    '#attributes' => $attr,
  );
  foreach (payment_get_methods() as $name) {
    if ($display_name = module_invoke($name, 'paymentapi', $edit, 'display name')) {
      $options[$name] = $display_name;
    }
  }
  $form['overview']['payment_method'] = array(
    '#type' => 'select',
    '#title' => t('Payment method'),
    '#default_value' => $edit->payment_method,
    '#options' => $options,
  );
  if ($edit) {
    $form['overview']['changed'] = array(
      '#type' => 'item',
      '#title' => t('Last modified'),
      '#value' => $edit->changed,
      '#size' => 30,
      '#maxlength' => 30,
    );
  }

  return $form;
}

/**
 * Creates a standard address form
 * When passed a display field we filter out all other sections and display only
 * the one given.  If passed shipping only a shipping address will be shown.
 * 
 * @param object $txn this is a transaction object used for default values
 * @param string $txn option field that allows the form to filter sections
 */
function store_transaction_addresses_form($txn, $display = null) {
  $form['address']['#tree'] = TRUE;

  $form['address']['shipping'] = store_address_form($txn->address['shipping']);
  $form['address']['shipping']['#type'] = 'fieldset';
  $form['address']['shipping']['#title'] = t('Shipping address');
  
  $form['address']['billing'] = store_address_form($txn->address['billing']);
  $form['address']['billing']['#type'] = 'fieldset';
  $form['address']['billing']['#title'] = t('Billing address');

  switch ($display) {
    case 'shipping':
      unset($form['billing']);
      break;

    case 'billing':
      unset($form['shipping']);
      break;
  }
  return $form;
}

function store_address_form($address) {
  if (isset($address->aid)) {
    $form['type'] = array(
      '#type' => 'hidden',
      '#value' => $address->type,
    );
  }

  $form['firstname'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#default_value' => $address->firstname,
    '#size' => 32,
    '#maxlength' => 32,
  );
  $form['lastname'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
    '#default_value' => $address->lastname,
    '#size' => 32,
    '#maxlength' => 32,
  );
  $form['street1'] = array(
    '#type' => 'textfield',
    '#title' => t('Street1'),
    '#default_value' => $address->street1,
    '#size' => 60,
    '#maxlength' => 64,
  );
  $form['street2'] = array(
    '#type' => 'textfield',
    '#title' => t('Street2'),
    '#default_value' => $address->street2,
    '#size' => 60,
    '#maxlength' => 64,
  );
  $form['city'] = array(
    '#type' => 'textfield',
    '#title' => t('City'),
    '#default_value' => $address->city,
    '#size' => 32,
    '#maxlength' => 32,
  );
  $form['state'] = array(
    '#type' => 'textfield',
    '#title' => t('State'),
    '#default_value' => $address->state,
    '#size' => 32,
    '#maxlength' => 32,
  );
  $form['zip'] = array(
    '#type' => 'textfield',
    '#title' => t('Zip'),
    '#default_value' => $address->zip,
    '#size' => 10,
    '#maxlength' => 10,
  );
  $form['country'] = array(
    '#type' => 'select',
    '#title' => t('Country'),
    '#default_value' => ($address->country ? $address->country : variable_get('ec_country', '')),
    '#options' => store_build_countries(),
  );
  return $form;
}

function store_transaction_item_form($edit) {

  $form = null;
  if (is_array($edit->item)) {
    foreach ($edit->item as $k => $p) {

      $form[$k] = array(
        '#type' => 'fieldset',
        '#title' => t('Item #%nid', array('%nid' => $p->nid))
        );
      $form[$k]['item'][$p->nid]['title'] = array(
        '#type' => 'textfield',
        '#title' => t('Title'),
        '#default_value' => $p->title,
        '#size' => 30,
        '#maxlength' => 128,
        );
      $form[$k]['item'][$p->nid]['qty'] = array(
        '#type' => 'textfield',
        '#title' => t('Quantity'),
        '#default_value' => $p->qty,
        '#size' => 15,
        '#maxlength' => 15,
        );
      $form[$k]['item'][$p->nid]['qty'] = array(
        '#type' => 'textfield',
        '#title' => t('Quantity'),
        '#default_value' => $p->qty,
        '#size' => 15,
        '#maxlength' => 15,
        );
      $form[$k]['item'][$p->nid]['data'] = array(
        '#type' => 'hidden',
        '#value' => $p->data,
        );

      $interval = drupal_map_assoc(range(0, 31));
      $interval[0] = '--';
      $unit = array(0 => t('-please choose-'), 'D' => t('Day(s)'), 'W' => t('Week(s)'), 'M' => t('Month(s)'), 'Y' => t('Year(s)'));

      if ($p->expires) {
        $p->expires = format_date($p->expires, 'custom', 'Y-m-d H:i O');
      }

      if ($p->is_recurring) {
        $form[$k]['recurring'] = array(
          '#type' => 'fieldset',
          '#title' => t('Billing cycle length'),
          );
        $form[$k]['recurring']['item'][$p->nid]['price_interval'] = array(
          '#type' => 'select',
          '#title' => t('Interval'),
          '#default_value' => $p->price_interval,
          '#options' => $interval,
          );
        $form[$k]['recurring']['item'][$p->nid]['price_unit'] = array(
          '#type' => 'select',
          '#title' => t('Unit'),
          '#default_value' => $p->price_unit,
          '#options' => $unit,
          );
        $interval[0] = 0;
        $interval[-1] = t('unlimited');
        $form[$k]['recurring']['item'][$p->nid]['price_cycle'] = array(
          '#type' => 'select',
          '#title' => t('Number of payments cycles'),
          '#default_value' => $p->price_cycle,
          '#options' => $interval,
          '#description' => t('Select the number of recurring payment cycles this product has.'),
          );
        $form[$k]['recurring']['item'][$p->nid]['expires'] = array(
          '#type' => 'textfield',
          '#title' => t('expires'),
          '#default_value' => $p->expires,
          '#size' => 21,
          '#maxlength' => 25,
          '#description' => t('The date the product will expire, or if using recurring payments, the date of the next billing cycle unless all payment cycles have ended.'),
          );
      }
    }
  }
  else {
    $form['products'] = array(
      '#type' => 'fieldset',
      '#title' => t('Products'),
      );
    $form['products']['nids'] = array(
      '#type' => 'textfield',
      '#title' => t('Product IDs'),
      '#default_value' => $edit->nids,
      '#autocomplete_path' => 'product/autocomplete',
      '#size' => 60,
      '#maxlength' => 128,
      '#description' =>  t('Enter a comma separated list of product ids to add to this transaction. Here is a <a href="%product_quicklist" onclick="window.open(this.href, \'%product_quicklist\', \'width=480,height=480,scrollbars=yes,status=yes\'); return false">list of all products</a>.', array('%product_quicklist' => url('admin/store/products/quicklist')))
      );
  }

  return $form;
}

/**
 * Format an address with carriage returns or HTML line breaks.
 *
 * @param $txn    Object containing users address information.
 * @param $type
 *   Is this a shipping or billing address.
 * @param $break
 *   Should we break using '\n' (text) or '<br />' (html)
 */
function store_format_address($txn, $type = 'shipping', $break = 'text') {
  // Cleanup values
  if ($break != 'text') {
    foreach ($txn->address[$type] as $key => $value) {
      if (is_scalar($value)) {
        $txn->address[$type]->$key = check_plain($value);
      }
    }
  }
  $break = ($break == 'text') ? "\n" : '<br />';
  if (!isset($txn->address[$type])) return false;

  $data = $txn->address[$type];

  $address = '';
  if ($data->firstname && $data->lastname) {
    $address .= ucfirst($data->firstname). ' '. ucfirst($data->lastname). $break;
    $address .= ($data->street2) ? $data->street1 . $break . $data->street2. $break : $data->street1. $break;
    $address .= ucfirst($data->city). ", ". strtoupper($data->state). " ". $data->zip. $break;
    $address .= store_get_country($data->country). $break;
    $address .= $data->phone. $break;
  }

  return $address;
}

/**
 * Format a shipping method.
 *
 * @param $txn
 *   Object containing shipping information.
 */
function store_format_shipping_method($txn) {
  $methods = module_invoke('shipping', 'get_methods');
  if ($txn->ship && is_array($methods)) {
    $method = $methods[$txn->ship->service][$txn->ship->method];
    return t('%service %method', array('%service' => $methods[$txn->ship->service]['#title'], '%method' => $methods[$txn->ship->service][$txn->ship->method]['#title']));
  }
  return t('none');
}
