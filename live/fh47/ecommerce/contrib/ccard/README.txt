Payment Module  : CCARD
Author          : Gordon Heydon
Settings        : > administer > settings > ccard

********************************************************************
DESCRIPTION:

CCard is an interface to most Australian banks using the ccard hosted
payment page product, which will give you real time payment processing
from your own site.

For more information on ccard please contact 
Bill Oborn <bill.oborn at skunkworks.net.au>

********************************************************************
SPECIAL NOTES:

Requires the curl php extension with openssl enabled.

********************************************************************
INSTALLATION:

1. Place the entire stgeorge_batch directory into your Drupal 
   modules/ecommerce/contrib/ directory.

2. Enable this module by navigating to:
   > administer > modules

3. Go to the module settings
   > administer > settings > ccard

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

********************************************************************
THANKS:

Special thanks to Mark Harrison <mark at slickfish.com.au> from slickfish
for help making this happen.

********************************************************************

See MAINTAINERS.txt for more maintenance info.
See README.txt (in E-Commerce root) for other info.