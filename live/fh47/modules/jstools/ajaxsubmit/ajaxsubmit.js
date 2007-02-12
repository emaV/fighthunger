// $Id: ajaxsubmit.js,v 1.4.2.1 2006/05/30 04:30:13 nedjo Exp $

/**
 * Attaches the ajaxsubmit behaviour to forms.
 */
function ajaxsubmitAutoAttach() {
  var forms = document.getElementsByTagName('form');
  for (i = 0; form = forms[i]; i++) {
    // Form must have an id.
    if (form && hasClass(form, 'ajaxsubmit') && form.elements['edit[form_id]']) {
      var messageId = form.elements['edit[form_id]'].value + '-message';
      if (!$(messageId)) {
        var messageDiv = document.createElement('div');
        addClass(messageDiv, 'ajaxsubmit-message');
        messageDiv.setAttribute('id', messageId);
        form.parentNode.insertBefore(messageDiv, form);
      }
      // Set a flag to indicate that the form is using ajaxsubmit.
      if (!form.ajaxsubmit) {
        var ajaxsubmitInput = document.createElement('input');
        with(ajaxsubmitInput) {
          setAttribute('type', 'hidden');
          setAttribute('name', 'ajaxsubmit');
          setAttribute('value', '1');
        }
        form.appendChild(ajaxsubmitInput);
      }
      var inputs = form.getElementsByTagName('input');
      for (j = 0; input = inputs[j]; j++) {
        if (input && (input.getAttribute('name') == 'op') && (input.getAttribute('type') == 'submit')  && hasClass(input, 'form-submit')) {
          // Generate a unique id for this button.
          if (!input.getAttribute('id')) {
            var buttonId = form.elements['edit[form_id]'].value + '-op-' + j;
            input.setAttribute('id', buttonId);
          }
          else {
            var buttonId = input.getAttribute('id');
          }
          new jsajaxsubmit(buttonId, messageId);
        }
      }
    }
  }
}

// Register the behavior.
jsTools.behaviors['ajaxsubmit'] = ajaxsubmitAutoAttach;

/**
 * JS ajaxsubmit object.
 */
function jsajaxsubmit(buttonId, messageId) {
  this.buttonId = buttonId;
  this.messageId = messageId;
  var buttonElt = $(buttonId);
  // The value will be the localized version of 'Submit' or 'Preview'.
  redirectFormButton(buttonElt.form.getAttribute('action'), buttonElt, this);
}

/**
 * Handler for the form redirection submission.
 */
jsajaxsubmit.prototype.onsubmit = function () {
  // Remove any error messages.
  var form = $(this.buttonId).form;
  for (var i = 0; elt = form.elements[i]; i++) {
    removeClass(elt, 'error');
  }
  var message = $(this.messageId);
  while(message.firstChild) {
    message.removeChild(message.firstChild);
  }
  // Insert progressbar.
  if (form.ajaxsubmit_progress) {

    // Success: redirect to the summary.
    var submitCallback = function (progress, status, pb) {
      if (progress == 100) {
        pb.stopMonitoring();
        window.location = '';
      }
    }

    // Failure: point out error message and provide link to the summary.
    var errorCallback = function (pb) {
      var div = document.createElement('p');
      div.className = 'error';
      div.innerHTML = 'An unrecoverable error has occured. You can find the error message below.';
      $('progress').insertBefore(div, $('progress').firstChild);
      $('wait').style.display = 'none';
    }
    this.progress = new progressBar('updateprogress', submitCallback, HTTPPost, errorCallback);
    this.progress.startMonitoring(form.ajaxsubmit_progress.value + (jsTools.query ? '&' : '?') + 'form_id=' + form.form_id.value, 0);
  }
  else {
    this.progress = new progressBar('ajaxsubmitprogress');
  }
  this.progress.setProgress(-1, 'submiting form');
  this.progress.element.style.width = '28em';
  this.progress.element.style.height = '200px';
  message.appendChild(this.progress.element);
}

/**
 * Handler for the form redirection completion.
 */
jsajaxsubmit.prototype.oncomplete = function (data) {
  // Remove progressbar
  removeNode(this.progress.element);
  this.progress = null;
  var message = $(this.messageId);
  message.innerHTML = data['message'];
  if (data['errors']) {
    for (id in data['errors']) {
      // edit[foo][bar] -> foo-bar
      if ($('edit-' + id.replace('][', '-'))) {
        addClass($('edit-' + id.replace('][', '-')), 'error');
      }
    }
  }
  // Set preview.
  if (data['preview']) {
    message.innerHTML += data['preview'];
  }
  // Redirect.
  if (data['destination']) {
    window.location = jsTools.query + data['destination'];
  }
  jsTools.effects.scrollTo(message);
  ajaxsubmitAutoAttach();
}

/**
 * Handler for the form redirection error.
 */
jsajaxsubmit.prototype.onerror = function (error) {
  // Remove progressbar
  removeNode(this.progress.element);
  this.progress = null;
  // Go to a designated error page, if any.
  var form = $(this.buttonId).form;
  var message = 
  $(this.messageId).innerHTML = form.elements['edit[ajaxsubmit_error_message]'] ? form.elements['edit[ajaxsubmit_error_message]'].value : 'An error occurred:<br /><br />'+ error;
  if (form.elements['edit[ajaxsubmit_error_redirect]']) {
    window.location.href = form.elements['edit[ajaxsubmit_error_redirect]'].value;
  }
}
