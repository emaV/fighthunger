Drupal formcheck.module README.txt
==============================================================================

Optionally adds checking to forms with user alerts when navigating away from
a page with unsaved changes.

On the administer > settings > formcheck you can select which forms to 
attach the behavior to. The module learns of new forms as they are displayed.

Based on code by m3verck, http://drupal.org/node/30220


Requirements
------------------------------------------------------------------------------
This module is written for Drupal 4.7.


Installation
------------------------------------------------------------------------------
Create a directory modules/formcheck (or, for easy updating, 
modules/jstools/formcheck) and copy all the module's files into it. Enable the
module via the administer > modules page.


Configuration
------------------------------------------------------------------------------
At administer > settings > formcheck you can select which forms to apply the 
behavior to. The module will learn about forms as they are generated, so
you may wish to return to this settings page after you've used the module 
for awhile.