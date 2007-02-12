Payment Module  : EWAY
Author          : Sammy Spets <http://drupal.org/user/8038>
Settings        : > administer > store > settings > payment (test mode)
                  > administer > store > settings > eway

********************************************************************
DESCRIPTION:

Eway is an Australian payment gateway that offers both hosted and
non-hosted payment methods. I'm going to stop there so it doesn't
sound like an advertisement since they aren't paying me to write
this.

********************************************************************
SPECIAL NOTES

Requires Curl PHP extension with SSL compiled in.

********************************************************************
CONFIGURATION:

1. Go to the payment module settings
   > admin > store > settings > payment
  
   You need to begin by enabling test mode so you can get a feel for
   how the module operates. Once you're ready to go live uncheck the
   box and save the settings to use the live payment site. Be certain
   to have your Client ID setup properly when you do this!
   
2. Go to the eway module settings
   > admin > store > settings > eway
   
   On this settings page you have three boxes. The first is Client ID.
   During test mode you should leave this box as 87654321. When you
   are ready to go live, change it to the one Eway provide you.

   The second box will vary depending on your setup.

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

********************************************************************
TESTING:

1. Use credit card number: 4646 4646 4646 4646
   Anything else will be invalid to Eway.

2. Optionally use CVN 123. Anything else will be invalid.

Main thing is that you enjoy! :)

********************************************************************

See MAINTAINERS.txt for more maintenance info.
See README.txt (in E-Commerce root) for other info.

