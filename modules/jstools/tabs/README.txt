Drupal tabs.module README.txt
==============================================================================

Created as part of a project sponsored by CivicSpace Labs, this module
provides methods for client-side tabbed pages, using the tab pane javascript
library created by Erik Arvidsson of webfx.


Requirements
------------------------------------------------------------------------------
This module is written for Drupal 4.7.


Installation
------------------------------------------------------------------------------
Create a directory modules/tabs and copy all the module's files into
it, as well as the folder 'css' and its contents.  Enable the module via
the administer > modules page.


Testing
------------------------------------------------------------------------------
To test the module, navigate to the page tabs/example.


Developer usage
-----------------------------------------------------------------------------
Tabbed pages are created in one of two ways: (a) through the Forms API or (b)
through direct calls to theme functions.


Forms API
----------------

tabs.module introduces two new element types: 'tabset' and 'tabpage'. 
Construct tabbed forms or form parts by nesting 'tabpage' elements within
a 'tabset' (and assigning the tabpages form elements). Each tabpage must have
a title--this will be the label on the rendered tab.

An example of a form with two tabs:

  $form['mytabset'] = array(
    '#type' => 'tabset'
  );
  $form['mytabset']['firsttab'] = array(
    '#type' => 'tabpage',
    '#title' => t('tab one')
  );
  $form['mytabset']['firsttab']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('name'),
    '#description' => t('Enter your full name')
  );
  $form['mytabset']['secondtab'] = array(
    '#type' => 'tabpage',
    '#title' => t('tab two')
  );
  $form['mytabset']['secondtab']['age'] = array(
    '#type' => 'textfield',
    '#title' => t('age'),
    '#description' => t('Enter your age')
  );


Theme calls
----------------

function theme_tabs_tab_page accepts two arguments: a label and the page content.

Example:

  $tabs .= theme('tabs_tab_page', t('First tab'), t('This is the first tabbed page'));
  $tabs .= theme('tabs_tab_page', t('Second tab'), t('This is the second tabbed page'));

Once you have a set of tabs, pass them to theme_tabs_tabset(), like this:

  $output = theme('tabs_tabset', 'example', $tabs);

'example' here is a unique name for the tabset.

If you need to refer to your tabs with other Javascript code, this name becomes
important. For example, you can find the currently-displayed tab's index as follows:

  var WebFXPane = document.getElementById('example');
  var selectedPane = WebFXPane.tabPane.getSelectedIndex();

Testing instructions:

* enable the tabs module
* enable the page module, log in as a user with admin access, and create a new piece of
  content ('node') of type 'page'
* for 'Input format', select 'PHP code'
* put the following as the page's content:

<?php

  $tabs .= theme('tabs_tab_page', t('First tab'), t('This is the first tabbed page'));
  $tabs .= theme('tabs_tab_page', t('Second tab'), t('This is the second tabbed page'));

  $output = theme('tabs_tabset', 'example', $tabs);

  print $output;

?>
