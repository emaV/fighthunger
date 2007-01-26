// $Id: collapsiblock.js,v 1.1.2.5 2006/05/30 04:30:13 nedjo Exp $

function collapsiblockAutoAttach() {
  // Different themes may use different tags for the block title. If there are others used, add them to this array.
  var headTags = ['h2','h3'];
  var divs = document.getElementsByTagName('div');
  for (var i = 0; div = divs[i]; i++) {
    var titleElt = null;
    if (div && hasClass(div, 'block')) {
      // Ensure we have a collapsable content block element.
      if (div.getElementsByTagName('div').length && hasClass(div.getElementsByTagName('div')[0], 'content')) {
        while (titleElt == null) {
          for (var j in headTags) {
            if (div.getElementsByTagName(headTags[j]).length) {
              titleElt = div.getElementsByTagName(headTags[j])[0];
              break;
            }
          }
        }
        if (titleElt) {
          // Status values: 1 = not collapsible, 2 = collapsible and expanded, 3 = collapsible and collapsed
          var status = jsTools.collapsiblockDefaults[div.id] ? jsTools.collapsiblockDefaults[div.id] : 2;
          if (status == 1) {
            continue;
          }
          titleElt.owner = div;
          addClass(titleElt, 'collapsiblock');
          // If user cookie says collapsed, or default status is collapsed an user cookie doesn't override, collapse.
          if((jsTools.helpers.getCookie('collapsiblock_' + div.id) == 'true') || ((status == 3) && !jsTools.helpers.getCookie('collapsiblock_' + div.id))) {
            addClass(div, 'collapsed');
            addClass(titleElt, 'collapsiblockCollapsed');
          }
          titleElt.onclick = function() {
            toggleClass(this.owner, 'collapsed');
            toggleClass(this, 'collapsiblockCollapsed');
            jsTools.helpers.setCookie('collapsiblock_' + this.owner.id, hasClass(this.owner, 'collapsed'));
            jsTools.attachBehaviors(['activemenu']);
          }
        }
      }
      // If any blocks have been collapsed (status = 3), they may have displaced activemenu elements.
      jsTools.attachBehaviors(['activemenu']);
    }
  }
}

jsTools.behaviors['collapsiblock'] = collapsiblockAutoAttach;
