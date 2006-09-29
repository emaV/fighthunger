********************************************************************
                     D R U P A L    M O D U L E
********************************************************************
Name: Ecommerce Package
Author: Matt Westgate <drupal at asitis dot org>

********************************************************************
DESCRIPTION:

This is a collection of modules used to drive a full-fledged
ecommerce system from within Drupal. The design for this project was
to create an extensible framework such that the payment methods,
shipping methods, and product types are built upon a pluggable
architecture.

If you sell shippable items, three shipping calculation methods come
with the package. Furthermore, you can enable inventory management
for the products whose stock you wish to track.

The store can be configured so that users can make a one-time
transaction, or they can create an user profile and have their
shipping and billing information retained from order to order,
speeding up the checkout process next time they return.

Finally, a workflow system has been built for transactions and
payment status, allowing you to easily send shipping notifications,
print invoices and update or manually create new orders.

Special thanks goes out to the Drupal developers, who were always
there for help and beta testing. Vive la Druplicon!

********************************************************************
INSTALLATION:

Note: It is assumed that you have Drupal up and running.  Be sure to
check http://drupal.org/handbook if you run into problems.

Preparing for Installation:
---------------------------

Note: Backing up your database is a very good idea before you begin!

1. Place the entire ecommerce directory into your Drupal modules/
   directory.

2. Load the database definition file (ecommerce.mysql) using the
   tool of your choice (e.g. phpmyadmin). For mysql and command line
   access use:

     mysql -u user -p drupal < ecommerce.mysql

   Replace 'user' with the MySQL username, and 'drupal' with the
   database being used.

3. The ecommerce package is a collection of modules. Enable these modules by
   navigating to:

     administer > modules

        Name      Req/Opt   Desc
        =====================================
        address   R   Used for storing user's billing and shipping addresses.
        cart      R   Shopping cart.
        file      O   Used for selling file downloads.
        parcel    R   Selling groups of products as a single item.
        payment   R   Payment API. You'll still need to enable a payment method such as paypal or COD.
        paypal    O   Use PayPal.com for payment processing.
        product   R   Product API for selling different types of products.
        shipping  R   Shipping API.
        store     R   Transaction creation and management.
        tangible  O   Used for selling shippable products.
		    tax		    O   Adds taxes based on rules.

        There are also many contributed ecommerce modules you can install as
        well. NOTE: You'll need to view the INSTALL.txt files for these
        contributed modules because for example, you may need to install a new
        database table. All ecommerce contributed modules live in
        ecommerce/contrib.

4. For the final configuration of the modules, navigate to

     administer > settings

   and click on the specific module name in the navagation tree to
   configure the module-specific options for each module.

6. Grant the proper access to user accounts under:

    administer > access control

7. Create new products via create content > product

8. Optionally, enable the cart block via

     administer > blocks :: Shopping Cart

For specific information regarding the other ecommerce modules,
take a look at the README and INSTALL files in their respective subdirectories.

********************************************************************
How to add images to products:

Here is an easy way to add images to products using image.module.

1. Make sure image.module is installed and enabled on your Drupal
   site.

2. Navigate to create content > image

3. Fill in the form fields and click 'submit'.

4. On the next page, click on the 'product' tab. This following
   steps will make this image a product as well.

5. Fill in all the form fields for the product information.

6. On the next page you'll see the image now has a price visible
   and an 'add to cart' link. The product view page
   ( http://www.example.com/index.php?q=product ) will also display
   a nice thumbnail of the image before the actual page view.
