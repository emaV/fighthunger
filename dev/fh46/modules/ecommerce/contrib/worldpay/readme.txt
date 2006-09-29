$Id: readme.txt,v 1.1 2006/01/05 00:45:47 jasonm3m Exp $

Introduction:

This module is an add-on Worldpay(tm) payments integration module for the 
Drupal ecommerce suite of modules.

Developed by a few drupallers, mainly rkendall and Dublin Drupaller.

This module is intended for Drupal version 4.6.x and later versions.

Tested and works with version 4.6.x

Updated 4th january 2006 by Dublin Drupaller

Things to do: Complete the advanced usage section to process payments automatically from the Worldpay(tm) website. 
At the moment It is recommended to redirect users to a generic THANK YOU node and process the tranactions in Drupal manually
on receipt of a payment confirmation email or notice on your Worldpay(tm) account.


INSTALLATION:

1. Upload the worldpay.module to the your /modules/ folder.

3. go to ADMINISTER -> MODULES and enable the worldpay module

4. Go to ADMINISTER -> SETTINGS -> WORLDPAY and setup your worldpay handshake.


BASIC USAGE:
The worldpay.module settings page should be self explanatory and it is recommended that you just
use a generic THANK YOU FOR SHOPPING WITH US style page until the module is updated to handle
the callbacks from worldpay automatically.

ADVANCED USAGE:
** for advanced programmers only **
If you are an experienced programmer and have worked out how to handle
callbacks from Worldpay, you can edit a temporary function (worldpay_callback_process)
included in the Worldpay.module (line numbers 230 - to - 336) to handsake and handle
callbacks automatically. At the time of release, I hadn't worked this out. 
So please submit a patch if you have the time.


Hope you find it useful. 

This module was developed for my own use and isn't intended as a fully released module (yet).
If you find it helpful or have any ideas for improving it. Please drop me an email.

Cheers

Dublin Drupaller
gus(at)modernmediamuse.com
