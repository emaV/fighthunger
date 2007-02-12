Drupal columns.module README.txt
==============================================================================

Enables multi-column layout, based on the css3-multi-column.js library by 
Cédric Savarese. The library is experimental, and so therefore is this module.


Requirements
------------------------------------------------------------------------------
This module is written for Drupal 4.7.


Installation
------------------------------------------------------------------------------
Create a directory modules/columns (or, for easy updating, 
modules/jstools/columns) and copy all the module's files into it. Enable the
module via the administer > modules page.


Usage
-----------------------------------------------------------------------------
To get content to display in columns, enclose it in a div with a class of either
"two-col", "three-col", or "four-col". Example:

<div class="two-col"><p>This is a two column section-or would be if it were long
enough.</p></div>