// $Id: jscalendar.js,v 1.4.2.4 2006/09/20 01:46:38 nedjo Exp $

if (isJsEnabled()) {
  addLoadEvent(function() {
    // Select all input elements
    inputs = document.getElementsByTagName('input');
    for (var i = 0; input = inputs[i]; ++i) {
      if (input && (input.getAttribute('type') == 'text') && hasClass(input, 'jscalendar')) {
        var form = input.form;
        var button = document.createElement('button');
        button.appendChild(document.createTextNode(' ... '));
        button.setAttribute('id', input.getAttribute('id') + '-button');
        addClass(button, 'jscalendar-icon');
        input.parentNode.insertBefore(button, input.nextSibling);
        addClass(input.parentNode, 'jscalendar');
        var settings = [];
        settings['ifFormat'] = $(input.id+'_jscalendar-ifFormat') ? $(input.id+'_jscalendar-ifFormat').value : '%Y-%m-%d %H:%M:%S';
        // We use eval() because the result is a boolean while our input is a string.
        settings['showsTime'] = $(input.id+'_jscalendar-showsTime') ? eval($(input.id+'_jscalendar-showsTime').value) : true;
        settings['timeFormat'] = $(input.id+'_jscalendar-timeFormat') ? $(input.id+'_jscalendar-timeFormat').value : '12';
        Calendar.setup(
          {
            inputField  : input.id,
            ifFormat    : settings['ifFormat'],
            button      : input.getAttribute('id') + '-button',
            showsTime   : settings['showsTime'],
            timeFormat  : settings['timeFormat']
          }
        );
      }
    }
  });
}
