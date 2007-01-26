// $Id: formcheck.js,v 1.1.2.2 2006/06/02 16:12:33 nedjo Exp $

// Global killswitch
if (isJsEnabled()) {
  window.onbeforeunload = formcheckFormCheck;
  addLoadEvent(formcheckAutoAttach);
}

var formcheckIsSubmit = false;

/**
 * Attaches a submit event behavior to all required forms
 */
function formcheckAutoAttach() {
  var forms = document.forms;
  for (var i = 0; form = forms[i]; i++) {
    if (form && hasClass(form, 'formcheck')) {
      addSubmitEvent(form, function() { formcheckIsSubmit = true });
    }
  }
}

/**
 * Checks to see if a form has changed
 */
function formcheckIsFormChanged() {
  var forms = document.forms;
  var form, element;
  
  // Loop through all forms
  for (var i = 0; form = forms[i]; i++) {
    if (form && hasClass(form, 'formcheck')) {
      // Loop through each element in each form
      for (var j = 0; element = form[j]; j++) {
        if (element.type && formCheckIsElementChanged(element)) {
          return true;
        } 
      }
    }
  }
  return false; 
}

/**
 * Checks to see if a form element has changed
 */
function formCheckIsElementChanged(el) {
  // Correct case of element type
  switch (el.type.toLowerCase()) {
    case 'text':
    case 'textarea':
    case 'password':
      if (el.value != el.defaultValue) {
        return true;
      }
      break;
    case 'radio':
    case 'checkbox':
      if (el.checked != el.defaultChecked) {
        return true;
      }
      break;
    case 'select-one':
    case 'select-multiple':
      for (var k=0; k < el.options.length; k++) {
        if (el.options[k].selected != el.options[k].defaultSelected) {
          return true;
        }
      }
      break;
  }
  
  return false;
}

/**
 * Before leaving a page, checks to see if any form has changed
 */
function formcheckFormCheck(e) { 
  if (!e && window.event) {
    e = window.event;
  }
  
  var isChanged = formcheckIsFormChanged();

  // We can fix this when we introduce a js localization solution.
  var message = 'You have unsaved changes.';

  // Don't run if submit button was clicked or form hasn't changed or there is no event
  if (!formcheckIsSubmit && isChanged && e) {
    e.returnValue = message;
    return message;
  }
}
