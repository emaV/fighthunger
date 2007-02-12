********************************************************************
                     D R U P A L    M O D U L E                         
********************************************************************
Name: contento module
Author: Jose A. Reyero
Email: drupal at reyero dot net

OVERVIEW:
=========
This module will allow any number of new simple, story like, content -node- types.
It can be used to define completely new content types or as a drop-in replacement for content types defined by other modules.

This is completely based on JonBob's CCK and Negiesy Karoly -chx- previous work, which means all of the ideas and most of the code have been just 'copied&pasted'.
And also, on this thread, http://drupal.org/node/62340 [Pave the way for CCK].

INSTALLATION:
============
Run the database script provided.
Enable the module.

Define new content types in administer>settings>content-types. A few new permissions will be created for each content type.
Set the right permissions for them in administer>access control.
Use them as any other simple node type.

To use this module as a replacement to handle other node types, disable the old module, and define a new content type with the right 'system name'. 
The 'contento' module will handle then nodes of that type. Don't forget to redefine the permissions.

NOTES:
======
The idea of this module is to be kind of 'transitional' tool, and implement some of the features that hopefully will be in the next Drupal version.

The name is just a try to avoid naming clashes with other modules. And it sounds good in Spanish ;-)

This is a simple lightweight module and I don't have the intention to extend it. If you want something more powerful, try Flexinode or CCK.


