********************************************************************
                     D R U P A L    M O D U L E
********************************************************************
Name: paypalpro module
Author: Jeremy Andrews <jeremy at kerneltrap dot org>
Last update: (See CHANGELOG.txt for details)
Drupal: 4.6
Dependencies:
  address.module
  cart.module
  payment.module
  store.module

  You must compile curl and openssl support into php.
********************************************************************
DESCRIPTION:

Accept payments using PayPal Pro. This module provides support for
both the Direct Payment API and the Express Checkout API.

********************************************************************
INSTALLATION:

Add paypalpro.mysql to your database and enable the paypalpro 
module.

It is advised that you first test this functionality with a
PayPal Sandbox.  For help on setting up a PayPal sandbox, try
this URL:
http://www.paypaldev.org/topic.asp?TOPIC_ID=10567
