Drupal extendeddescription.module README.txt
==============================================================================

Minor little widget for adding an extended description to form elements. 
Include a ['#extendeddescription'] in a form element and, if js is enabled, a
link will appear beside the label, clicking which will display additional
text (the value of ['#extendeddescription']) below the form element.


Requirements
------------------------------------------------------------------------------
This module is written for Drupal 4.7 and requires the jstools.module to be
enabled.


Installation
------------------------------------------------------------------------------
Create a directory modules/extendeddescription (or, for easy updating, 
modules/jstools/extendeddescription) and copy all the module's files into it. Enable the
module via the administer > modules page.


Developer Usage
------------------------------------------------------------------------------
Add extended description to a form element as follows:

 $form['date'] = array(
   '#type' => 'textfield',
   '#extendeddescription' => t('This is additional information about the field.')
 );
