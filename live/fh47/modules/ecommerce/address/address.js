// $Id: address.js,v 1.1.2.3 2006/09/26 02:55:50 gordon Exp $

if (isJsEnabled()) {
  addLoadEvent(addressAutoAttach);
}

function addressAutoAttach() {
  ctry = document.getElementById('edit-country');
  provORstate(ctry.options[ctry.selectedIndex].value);
  document.getElementById('edit-state').remove(document.getElementById('edit-state').length-1);
}

function provORstate(val) {
  if (val == 'us') {
    document.getElementById('edit-province').parentNode.style.display = 'none';
    document.getElementById('edit-province').value = '';
    document.getElementById('edit-state').parentNode.style.display = 'inline';
  }
  else {
    document.getElementById('edit-province').parentNode.style.display = 'inline';
    document.getElementById('edit-state').parentNode.style.display = 'none';
  }
}
//this stuff allows functionality for non-JavaScript browsers
