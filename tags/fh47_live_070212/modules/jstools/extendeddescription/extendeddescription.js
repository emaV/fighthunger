// $Id: extendeddescription.js,v 1.1.2.2 2006/05/30 04:30:13 nedjo Exp $

function extendeddescriptionAutoAttach() {
  var divs = document.getElementsByTagName('div');
  for (var i = 0; div = divs[i]; i++) {
    if (div && hasClass(div, 'extendeddescription')) {
      addClass(div, 'extendeddescription-collapsed');
      // Parse out the form element id.
      var eltId = div.getAttribute('id').substring(20, div.getAttribute('id').length);
      var a = document.createElement('a');
      a.className = 'extendeddescription-trigger';
      a.setAttribute('href', '');
      a.owner = div;
      a.onclick = function() {
        removeClass(this.owner, 'extendeddescription-collapsed');
        this.parentNode.removeChild(this);
        return false;
      }
      a.appendChild(document.createTextNode('What\'s this?'));
      var appended = false;
      // Look for a label.
      var labels = document.getElementsByTagName('label');
      for (var j = 0; label = labels[j]; j++) {
        if (label.getAttribute('for') == eltId) {
          label.appendChild(a);
          appended = true;
          break;
        }
      }
      // If we didn't find a label, append before the hidden description.
      if (!appended) {
        div.parentNode.insertBefore(a, div);
      }
    }
  }
}

jsTools.behaviors['extendeddescription'] = extendeddescriptionAutoAttach;