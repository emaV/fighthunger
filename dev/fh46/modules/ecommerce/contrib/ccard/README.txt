********************************************************************
                     D R U P A L    M O D U L E
********************************************************************
Name: ccard module
Author: Gordon Heydon <gordon at heydon.com.au>
Dependencies:
  This module requires the e-commerce package to work properly and
  the curl php extension with openssl enabled.

DESCRIPTION:
********************************************************************
CCard is an interface to most Australian banks using the ccard hosted
payment page product, which will give you real time payment processing
from your own site.

For more information on ccard please contact 
Bill Oborn <bill.oborn at skunkworks.net.au>

INSTALLATION:
********************************************************************
1. Place the entire stgeorge_batch directory into your Drupal 
   modules/ecommerce/contrib/ directory.

2. Apply the ccard.mysql to the drupal database to create the
   required tables.

     mysql -u {username} -p drupal < ccard.mysql

   Or if you are using phpmyadmin or the drupal dba module the upload the
   ccard.mysql as a sql query.

3. Enable this module by navigating to:

     administer > modules

4. Go to the module settings

     administer > settings > ccard

   You will need to the enter you ccard clientid. to be able to
   start downloading you batches from your web site.

   By default the payment method will work with no encryption on the
   browser. If you have a SSL certificate for your web site you can change
   the url to point to the https version of the payments page.

   You can also use a Shared SSL certificate in much the same method but you
   do need to make sure that you installation of drupal has been configured
   to work through the shared SSL site.

   Also the configuration of a thank you page is also a good thing, and
   allows you to move the user back to the non SSL site.

THANKS:
********************************************************************
Special Thanks to Mark Harrison <mark at slickfish.com.au> from slickfish
for help make this happen.
