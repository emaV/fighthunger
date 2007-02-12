Payment Module  : ITRANSACT
Author          : Sammy Spets <http://drupal.org/user/8038>
Settings        : > administer > store > settings > payment (test mode)
                  > administer > store > settings > eway
                  
********************************************************************
DESCRIPTION:

The itransact module enables your web store to accept payments
through iTransact's payment gateway (www.itransact.com). You need to
obtain an account with them before using this module. They only
allow testing for accountholders.

You need to enable XML payments on your iTransact account as well.

********************************************************************
SPECIAL NOTES

Requires Curl PHP extension with SSL compiled in.

This module currently only transacts using the XML method, which
means you'll be charged $5 per month on top of your normal monthly
charges. Please sponsor a Drupal developer to add HTML payment
support.

This module does not support CVV and AVS. Please sponsor a Drupal
developer to add support for these.

This module does not support cheque (check) payments. Please sponsor
a Drupal developer to add support for this.

********************************************************************
CONFIGURATION:

1. Go to the payment module settings
   > admin > store > settings > payment
  
   You need to begin by enabling test mode so you can get a feel for
   how the module operates. Once you're ready to go live uncheck the
   box and save the settings to use the live payment site. Be certain
   to have your Client ID setup properly when you do this!
   
2. Go to the eway module settings
   > admin > store > settings > itransact
   
   On this settings page you have three boxes. The first is Client ID.
   Set this to the one iTransact provide you. Same for the Client
   Password.

   The third box will vary depending on your setup. For the moment you
   can leave it as the default.

   By default the payment method will work with no encryption on the
   browser. If you have a SSL certificate for your web site you can
   change the url to point to the https version of the payments page.

   You can also use a Shared SSL certificate in much the same method
   but you do need to make sure that you installation of drupal has
   been configured to work through the shared SSL site.

   Also the configuration of a thank you page is also a good thing,
   and allows you to move the user back to the non SSL site. The 
   default will be fine, but you can move the user to the products 
   page or perhaps a payment accepted page. Your choice! :)

   I personally recommend pointing it to store/history so they get a
   list of the orders they have placed and can manipulate them.

3. Go to the iTransact site and disable all emails being sent to
   your customers. You'll still receive emails for all payments.

4. On the iTransact site, add the IP address of the SSL server that
   shows the payment page. This goes into the IP Filter (XML) bit.

5. Enable testing mode through the iTransact control panel if you
   are testing the system.

********************************************************************
TESTING:

1. MAKE SURE YOU HAVE ENABLED THE TESTING MODE or you'll be charged
   for the transaction! This is done through your iTransact control
   panel.

2. Use credit card number: 5454 5454 5454 5454
   Anything else will be invalid to iTransact.

********************************************************************
THANKS:

Big thank you to Urbits and the sponsorship of:
   * Richard Bychowski (http://hiranyaloka.com)
   * iTransact.
