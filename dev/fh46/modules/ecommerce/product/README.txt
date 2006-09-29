********************************************************************
                     D R U P A L    M O D U L E                         
********************************************************************
Name: Product module
Author: Matt Westgate <drupal at asitis dot org>
Last update: Apr 18, 2004 (See CHANGELOG for details)
Drupal: CVS
Dependencies:
  node.module
********************************************************************
DESCRIPTION:

Product API for the ecommerce module. This module itself doesn't 
create products, rather it is an interface for product creation. 
Currently there are two 'product creation' modules available: 
tangible_product and multi_product.

Please note that the concept of product types is different than 
product categories.  When i refer to types of i asking whether or 
not the product is a download, or a shippable item or even a 
service.  Product categories are simply taxonomies that can be 
applied to any product type.

********************************************************************
INSTALLATION:

see the INSTALL file in this directory.

********************************************************************
WISH LIST:

- Create a file download product type using the new file API.
