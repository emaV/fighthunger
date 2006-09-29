********************************************************************
                     D R U P A L    M O D U L E
********************************************************************
Name: authorize_net module
Author: Matt Westgate <drupal at asitis dot org>
Last update: (See CHANGELOG.txt for details)
Drupal: 4.5
Dependencies:
  payment.module
  address.module
  store.module

  You must also have curl compiled with php.
********************************************************************
DESCRIPTION:

Accept payments using Authorize.net. This module uses the Advanced
Integration Method (AIM) to submit transactions to the payment
gateway.

This module was written for a pair.com server and the way they
offer SSL hosting. Your mileage may very, but I've tried to
document everything I had to do to get it up and running.

********************************************************************
INSTALLATION:

see the INSTALL.txt file in this directory.

********************************************************************
WISH LIST:

- It currently doesn't handle Card Code Verification (CCV), which is
  the three digits on the back of credit cards. If you enable this as
  part of your card code settings, transactions will fail.

- This module does not work using shared SSL certificates. So for
  example https://ssl20.pair.com/username/ will not work is this is
  not your base_url as configured in includes/conf.php. However if
  your base_url is http://www.example.com/ and the SSL URL is
  https://www.example.com/, this module will function as expected.
  You must obtain a secure server certificate from a Certificate
  Authority ("CA") to have this functionality.
