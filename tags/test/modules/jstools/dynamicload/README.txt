Drupal dynamicload.module README.txt
==============================================================================

*** Warning: This module is under active development, and hasn't been fully ***
*** tested for potential security issues.                                   ***

Module for implementing in-place content loading in Drupal, early beta.

When enabled, block links are replaced with javascript calls to dynamically
load only the requested page's content.

To test:

* Install and enable module
* Go to block configuration and click 'configure' for a block.
* For the settings in the 'dynamic loading' fieldset, select
  'Apply dynamic loading' and designate a target area (main content
  area or one of the enabled blocks).

Example usages (for testing):

A. Menu loads into main content area

1. With menu module, create a menu with links to several pages (nodes, etc.).
   Ideally, the pages would have little content, e.g., a short list of links.
   Enable and position the newly created menu block.
2. Configure the block created in step 1. Check 'Apply dynamic loading'
   and select "Main content area" as the target.

When users click on menu items, they load into the main content area.

B. A  menu block loads into a custom block.

1. With menu module, create a menu with links to several pages (nodes, etc.).
   Ideally, the pages would have little content, e.g., a short list of links.
   Enable and position the newly created menu block.
2. With the block module, create a block that will be the 'target' (where 
   content loads). Enable and position the newly created block.
3. Configure the block created in step 1. Check 'Apply dynamic loading'
   and select the block created in step 2 as the target.

When users click on links in the menu block, the content load into
the block module block. (Note: some page elements are left out for
pages loading into blocks instead of the main content area--breadcrumbs
tabs, etc.).

CHANGELOG:
April 26, 2006:
* UI for designating which blocks/content areas
  to apply this behavior to, and where to load content to.
  In block configuration, choose another block or main content
  area as target.

TODO:

* Prevent loading of pages where this will wreck functionality
* Deal with passing arguments to callbacks (partially done).
* Generate other page elements as appropriate.
  - test to see if there are blocks to be displayed, pass them
    with data like region, and then on the client either replace existing
    block with that id or, if none, insert into correct region.
    Region functionality would require region ids in themes,
    see issue http://drupal.org/node/60552.
* Security audit
* Call other behaviors so that they are attached to new content elements.
* Etc....

Requirements
------------------------------------------------------------------------------
This module is written for Drupal 4.7 and requires the jstools.module to be
enabled.