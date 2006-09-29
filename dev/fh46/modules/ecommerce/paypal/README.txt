********************************************************************
                     D R U P A L    M O D U L E                         
********************************************************************
Name: PayPal module
Author: Matt Westgate <drupal at asitis dot org>
Last update: Apr 19, 2004 (See CHANGELOG for details)
Drupal: CVS
Dependencies:
  payment.module
  address.module
  store.module
********************************************************************
DESCRIPTION:

Accept payments using PayPal. This module also interacts with 
PayPal's Instant Payment Notification (IPN) system. It will 
automatically build the links to send to PayPal.

********************************************************************
INSTALLATION:

see the INSTALL file in this directory.

********************************************************************
WISH LIST:

- Have 'donate' and possibly even 'buy now' links where you don't 
  have to actually create a product. For example user's could have 
  their own donate links for responding to tech support requests 
  (This was Moshe's idea). I'm thinking this solution would be best 
  implemented with the macrotags module.
