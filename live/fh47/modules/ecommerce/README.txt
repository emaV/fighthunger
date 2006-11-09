********************************************************************
                     D R U P A L    M O D U L E
********************************************************************
Name: Ecommerce Package (For drupal 4.7.x)

Current Maintainer : Gordon Heydon http://drupal.org/user/959
Original Author    : Matt Westgate (not a current maintainer)

General Links:
Project Page       : http://drupal.org/project/ecommerce
Handbook           : http://drupal.org/node/50350
Support Queue      : http://drupal.org/project/issues/ecommerce
IRC                : irc.freenode.org > #drupal-ecommerce
Mailing List       : http://lists.heydon.com.au/listinfo.cgi/ecommerce-heydon.com.au
                     (Mailing list subject to change)

Developer Links:
Developers API     : http://ecommerce.heydon.com.au/api/ecommerce/function
Developers Docs    : http://drupal.org/node/52325
                     (Handbook pages are in development)

********************************************************************
DESCRIPTION:

This package aims to provide a framework for a complete ecommerce
solution for your website.

Product modules are available that allow you to sell a variety of product
types, from physical "tangible" products to virtual "files" or
perhaps "parcels" containing multiple items.

Shipping has changed in 4.7 with a new shipping api paving the
way for many more shipping plug-ins than have been available.
There is currently ongoing support for UPS/Fedex shipping via the
shipcalc module.

Payment modules cover many popular payment gateways and in addition
you can opt for "COD" which allows alternative forms of
payment.

A transaction system allows you to track and manage your orders,
changing the status as necessary and sending invoices and shipping
notifications.


********************************************************************
INSTALLATION:

Note: It is assumed that you have Drupal up and running. If you are
having problems, please review the Handbook, especially
http://drupal.org/node/43767

Preparing for Installation:
---------------------------
Note: Please back up your site and database.

1. Place the entire ecommerce directory into your Drupal /modules/
   directory. Alternatively, it can be placed in
   /sites/default/modules which effectively separates it from Drupal
   core modules.

2. As of 4.7, you do *not* need to install the database tables
   manually.

3. Enable your choice of modules by going to:
   > administer > modules.

   Because E-Commerce contains a large number of modules, the module
   installation screen can be confusing. Look at the end of this file
   for a summary of the ecommerce entries you'll find there,
   including a list of required ones.

4. For the final configuration of the modules, navigate to a menu
   location which is new for 4.7:
   > administer > store > settings

   Click on the module name in the navagation tree to
   configure module-specific options.

6. Grant the proper access to user accounts under:
   > administer > access control

7. Create new products via create
   > create content > product

8. Optionally, enable the cart block via
   > administer > blocks :: Shopping Cart


********************************************************************
Modules Summary and 4.7 Release Status.


Type            Name                 Status   Info
====================================================================
REQUIRED        cart                          http://drupal.org/node/66650
                payment                       http://drupal.org/node/66801
                product                       http://drupal.org/node/50359
                shipping
                store

--------------------------------------------------------------------
Product       	apparel              cvs
                auction              4.7
                custom               cvs
                donate               4.7
                file                 4.7      http://drupal.org/node/66654
                generic              4.7
                hosting              cvs
                parcel               4.7      http://drupal.org/node/66798
                service              cvs
                tangible             4.7
                subproducts          4.7      http://drupal.org/node/52743

--------------------------------------------------------------------
Payment         assist               cvs
                authorize_net        4.7
                ccard                4.7
                cod                  4.7      http://drupal.org/node/66652
                eway                 4.7
                exact                cvs
                itransact            4.7
                linkpoint_api        cvs
                paypal               4.7
                paypalpro            cvs
                paypalpro_express    cvs
                worldpay             4.7

--------------------------------------------------------------------
Pricing         coupon               4.7
                role_discount        4.7
                tax                  4.7

--------------------------------------------------------------------
Shipping        shipcalc             4.7

--------------------------------------------------------------------
Contact/Addr    address              4.7      http://drupal.org/node/66072
          	    ecivicrm             4.7
           	    ecommailer           cvs

--------------------------------------------------------------------
Other           recommend            cvs
                stores               cvs
                submanage            cvs
                ec_devel             4.7

--------------------------------------------------------------------
