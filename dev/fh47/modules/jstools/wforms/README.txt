Drupal wforms.module README.txt
==============================================================================

Created by CivicSpace Labs as part of a project for GoodStorm, this module
provides methods for client-side interactive forms using the wForms library
by Cédric Savarese (http://www.4213miles.com). See wForms documentation at

http://www.formassembly.com/wForms/


Requirements
------------------------------------------------------------------------------
This module is written for Drupal 4.7.


Installation
------------------------------------------------------------------------------
Create a directory modules/wforms (or, for easy updating, 
modules/jstools/wforms) and copy all the module's files into it, as well 
as the folder 'lib' and its contents. Enable the module via the 
administer > modules page.


Developer usage
------------------------------------------------------------------------------

Multi-page forms
----------------------

Each page of the form is an element of type 'wformspage'. This 
element type is basically a wrapper that encloses form sections in
a div with the appropriate class names to trigger the wForms multi-
page behavior.

Construct a multi-page form as follows:

  // First page
  $form['page1'] = array(
    '#type' => 'wformspage',
    '#title' => t('Page 1')
  );
  $form['page1']['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#description' => t('Give a title to your item'),
    '#required' => TRUE,
    '#size' => 30
  );

  // Second page
  $form['page2'] = array(
    '#type' => 'wformspage',
    '#title' => t('Page 2')
  );
  $form['page2']['body'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#description' => t('Describe your item'),
    '#rows' => 3,
    '#cols' => 30,
    '#required' => TRUE
  );

Validation
----------------------

Validation for required fields works out of the box, as wForms recognizes
Drupal 'required' class names.

For other types of validation, add classes that trigger validation
behaviors--see the list of supported selectors at:

http://www.formassembly.com/blog/input-validation-explained/

Example:

  $form['page1']['email'] = array(
    '#type' => 'textfield',
    '#title' => t('Email'),
    '#size' => 30,
    '#attributes' => array('class' => 'validate-email')
  );

Further functionality
----------------------

Other behaviors can be added through adding classes and enclosing divs
to form elements (e.g., using form elements' '#prefix' and '#suffix' selectors).

See the wForms documentation, http://www.formassembly.com/wForms/