********************************************************************
                     D R U P A L    M O D U L E                         
********************************************************************
Name: Address module
Author: Matt Westgate <drupal at asitis dot org>
Last update: Feb 7, 2004 (See CHANGELOG for details)
Drupal: CVS
Dependencies: 
  user.module
********************************************************************
DESCRIPTION:

Allows users to keep a list of addresses associated with their 
profile.  It was initially built as a way for users to save shipping 
and billing addresses for ecommerce modules.

An 'address book' link will be created below the users 'my account'
menu link allowing the user to add/edit their addresses.

********************************************************************
INSTALLATION:

see the INSTALL file in this directory.

********************************************************************
WISH LIST:

- Categorize addresses. Probably a global taxonomy?

- Assign default addresses for each category. 
  (i.e., default billing address)

- Have the ability to make certain addresses available for public 
  viewing and a way to view all public addresses at once. That way 
  this code could function as a Relationship Management tool, 
  allowing users to manage their set of public contacts.
