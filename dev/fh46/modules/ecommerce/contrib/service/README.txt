********************************************************************
                     D R U P A L    M O D U L E
********************************************************************
Name: Service module
Author: Khalid Baheyeldin http://baheyeldin.com/khalid
Based on dontate module by Matt Westgate <drupal at asitis dot org>

Description:
------------
This modules allows selling a variable rate service using the 
E-commerce package.

There is no price set for the service, so
the user has to enter the dollar amount for the service, as per
agreement with the site owner.

This is useful for getting paid via Paypal for things like design
and programming project.

Dependancies:
-------------
This module requires the ecommerce packageto work properly.

Installation:
------------
1. Place the entire service directory into your Drupal modules/ecommerce/contrib/
   directory.

2. Enable this module by navigating to:

     administer > modules

Creating a service product:
---------------------------

The next time you create a product, you'll see a 'service' item in
the product type drop-down box.

When creating donation product types, choose 0 for the price and select
the 'hide' option for the 'add to cart' link.

Wishlist:
---------

- It would be nice if there is a linkage to billing in ecommerce. For
  example, the site owner would send an invoice by email to a specific
  address, with instructions on how to pay. The user would then have
  the amount pre-entered for them, and they pay it.

  However, we are delving here into CRM territory.
