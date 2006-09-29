********************************************************************
                     D R U P A L    M O D U L E
********************************************************************
Name: exact module
Author: Matt Westgate <drupal at asitis dot org>
Drupal: 4.6
Dependencies:
  payment.module
  address.module
  store.module

Module development sponsored by E-xact Transactions Ltd.

********************************************************************
DESCRIPTION:

Accept payments using E-Xact payment processor. You'll need a
merchant account from your bank and an account with E-Xact
Transactions. Please go to http://www.e-xact.com for more details.


********************************************************************
INSTALLATION:

See the INSTALL.txt file in this directory.

********************************************************************
WISH LIST:

- This module does not work using shared SSL certificates. So for
  example https://ssl20.pair.com/username/ will not work is this is
  not your base_url as configured in includes/conf.php. However if
  your base_url is http://www.example.com/ and the SSL URL is
  https://www.example.com/, this module will function as expected.
  You must obtain a secure server certificate from a Certificate
  Authority ("CA") to have this functionality.
