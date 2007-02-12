// $Id: customization.js,v 1.1 2006/06/02 15:34:18 nedjo Exp $

// wForms - a javascript extension to web forms.
// Customization Example (wForms v0.94 - April 26 2005)
// Copyright (c) 2005 Cédric Savarese <pro@4213miles.com>
// This software is licensed under the CC-GNU LGPL <http://creativecommons.org/licenses/LGPL/2.1/>

//The Repeat behavior preserves the name attribute of radio inputs 
// accross repeated elements, effectively expanding the radio group. 
// Set to ‘false’ to make repeated radio inputs independant.
wFORMS.preserveRadioName = true; // default: true.

// The validation routine will display an alert box if there’s an error, with the message in wf.arrErrorMsg[8]
wFORMS.showAlertOnError = true; // default: true. 

// Error message displayed in the alert box.
wFORMS.arrErrorMsg[8] = "%% error(s) detected. Your form has not been submitted yet.\nPlease check the information you provided."; // %% will be replaced by the actual number of errors.

// overwrite the default form submission handler with a custom one.
wFORMS.functionName_formValidation = "customValidation";

//e : onSubmit event
function customValidation(e) {
  // Kill if we're in the midst of a file upload.
  // We test for a given line in the iframehandler function
  // set by function redirectFormButton() in drupal.js.
  if (window.iframeHandler) {
    var func = window.iframeHandler.toString();
    if(func.indexOf('button.form.action = action') > 0) {
      return;
    }
  }
  /** 
   * This is an alternate approach, but hard-coded to the 
   * 'fileop' id, so dropped in favour of the above.
   */
  if (document.getElementById('fileop') && (document.getElementById('fileop').onclick != null)) {
    return;
  }

  if(wf.formValidation(e)) {	// call the default error management.
    // basic wForms Validation Ok... can do other stuff here
    // if validation not ok, use return wf.utilities.XBrowserPreventEventDefault(e);
    return true;
  } else {
    return wf.utilities.XBrowserPreventEventDefault(e);  // will prevent the form from being submitted.
  }
}
