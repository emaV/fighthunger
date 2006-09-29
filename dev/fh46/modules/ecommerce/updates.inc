<?php
/* $Id: updates.inc,v 1.6.2.5 2005/10/30 19:02:46 mathias Exp $ */

/**
 * @file
 * All incremental database updates performed between Drupal releases.
 */

// Define the various updates in an array("date : comment" => "function");
$sql_updates = array(
  '2005-02-05: first update since Drupal 4.5 release' => 'update_1',
  '2005-02-20' => 'update_2',
  '2005-04-08' => 'update_3',
  '2005-04-09' => 'update_4',
  '2005-04-11' => 'update_5',
  '2005-04-23: first update since Drupal 4.6 release' => 'update_6',
  '2005-06-09' => 'update_7',
  '2005-06-25' => 'update_8',
  '2005-06-30' => 'update_9',
  '2005-10-02' => 'update_10'
);

function update_1() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql('ALTER TABLE {ec_cart} ADD data TEXT');
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_2() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql("ALTER TABLE {ec_store_transaction} RENAME {ec_transaction}");
    $ret[] = update_sql("UPDATE sequences SET name = '{ec_transaction}_txnid' WHERE name = '{ec_store_transaction}_txnid'");
    $ret[] = update_sql("ALTER TABLE {ec_store_transaction}_product RENAME {ec_transaction_product}");
    $ret[] = update_sql("ALTER TABLE {ec_transaction_product} CHANGE pid nid INT(10) UNSIGNED DEFAULT '0' NOT NULL");
    $ret[] = update_sql("ALTER TABLE {ec_transaction_product} DROP details");
    $ret[] = update_sql("ALTER TABLE {ec_transaction_product} ADD expires INT UNSIGNED DEFAULT '0' NOT NULL, ADD is_recurring TINYINT UNSIGNED DEFAULT '0' NOT NULL, ADD price_interval TINYINT UNSIGNED DEFAULT '0' NOT NULL, ADD price_unit VARCHAR(5) NOT NULL, ADD price_cycle INT DEFAULT '0' NOT NULL, ADD auto_charge TINYINT UNSIGNED DEFAULT '0' NOT NULL, ADD last_notify INT UNSIGNED DEFAULT '0' NOT NULL");
    $ret[] = update_sql("CREATE TABLE {ec_renewal} (txnid int(10) unsigned NOT NULL default '0', rtxnid int(10) unsigned NOT NULL default '0', rnid int(10) unsigned NOT NULL default '0', KEY txnid (txnid));");
    $ret[] = update_sql("ALTER TABLE {ec_product} ADD is_recurring TINYINT UNSIGNED DEFAULT '0' NOT NULL AFTER ptype, ADD price_interval TINYINT UNSIGNED DEFAULT '0' NOT NULL AFTER is_recurring, ADD price_unit VARCHAR( 5 ) NOT NULL AFTER price_interval, ADD price_cycle INT DEFAULT '0' NOT NULL AFTER price_unit, ADD auto_charge TINYINT UNSIGNED DEFAULT '0' NOT NULL AFTER price_cycle");
    $ret[] = update_sql("ALTER TABLE {ec_product_parcel} CHANGE mpid mnid INT(10) UNSIGNED DEFAULT '0' NOT NULL");
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_3() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql('ALTER TABLE {ec_product} CHANGE price_cycle price_cycle TINYINT(3) DEFAULT 0 NOT NULL');
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_4() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql('ALTER TABLE {ec_address} ADD phone VARCHAR(64) NOT NULL AFTER street2');
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_5() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql('ALTER TABLE {ec_address} CHANGE country country VARCHAR(64) DEFAULT 0 NOT NULL');

    $countries = array_flip(address_build_countries());

    $result = db_query("SELECT a.aid, a.state, c.name as country FROM {ec_address} a INNER JOIN {ec_country} c ON c.cid = a.country");
    while ($address = db_fetch_object($result)) {
      if ($key = $countries[$address->country]) {
        db_query("UPDATE {ec_address} SET country = '%s' WHERE aid = %d", $key, $address->aid);
      }
    }

    $ret[] = update_sql('DROP TABLE ec_country');
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_6() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql('ALTER TABLE ec_transaction_product ADD product_data TEXT AFTER expires');
    $ret[] = update_sql("CREATE TABLE ec_per_product_shipping (nid int(10) unsigned NOT NULL default '0', shipping_fee decimal(10,2) unsigned NOT NULL default '0.00', PRIMARY KEY (nid)) TYPE=MyISAM;");
    $ret[] = update_sql('ALTER TABLE ec_transaction_product ADD INDEX (txnid)');
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_7() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql('DROP TABLE {ec_payment_status}');
    $ret[] = update_sql('DROP TABLE {ec_transaction_workflow}');
    $ret[] = update_sql('ALTER TABLE {ec_transaction} CHANGE shipping_country shipping_country CHAR(2) NOT NULL DEFAULT 0, CHANGE billing_country billing_country CHAR(2) NOT NULL DEFAULT 0');
    $ret[] = update_sql('ALTER TABLE {ec_address} CHANGE country country CHAR(2) NOT NULL DEFAULT 0');
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_8() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql('DROP TABLE {ec_product_availability}');
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_9() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql('ALTER TABLE {ec_transaction_product} CHANGE product_data data TEXT DEFAULT NULL');
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_10() {
  if ($GLOBALS['db_type'] == 'mysql') {
    $ret[] = update_sql("CREATE TABLE ec_tmp (sid varchar(32) NOT NULL default '', tmp text NOT NULL, PRIMARY KEY  (sid));");
  }
  else {
    // pgsql goes here.
  }
  return $ret;
}

function update_sql($sql) {
  $edit = $_POST["edit"];
  $result = db_query($sql);
  if ($result) {
    return array('1', nl2br(htmlentities($sql)) ." ", "<div style=\"color: green;\">OK</div>\n");
  }
  else {
    return array('0', nl2br(htmlentities($sql)) ." ", "<div style=\"color: red;\">FAILED</div>\n");
  }
}

?>
