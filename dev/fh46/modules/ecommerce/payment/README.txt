********************************************************************
                     D R U P A L    M O D U L E                         
********************************************************************
Name: Payment module
Author: Matt Westgate <drupal at asitis dot org>
Last update: Apr 19, 2004 (See CHANGELOG for details)
Drupal: CVS
Dependencies:
  address.module
  store.module
********************************************************************
DESCRIPTION:

An API that allows you to process payments for ecommerce items. You 
will need to write the interface code to actually process the 
payments, or use a processing company that we have already written 
the interface for, such as PayPal.  

This module is responsible for sending the email invoice, gathering
up all available payment methods at checkout time, and some managing 
of payment status and workflow issues.

********************************************************************
INSTALLATION:

see the INSTALL file in this directory.

********************************************************************
WISH LIST:

- none at this time
