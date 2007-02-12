// $Id: dynamicload.js,v 1.7 2006/05/11 18:31:29 nedjo Exp $

function dynamicloadAutoAttach() {
  for (var id in jsTools.dynamicloadDefaults) {
    // Only proceed if both source and target elements exist.
    if ($(id) && $(jsTools.dynamicloadDefaults[id])) {
      var links = $(id).getElementsByTagName('a');
      for (var i = 0; link = links[i]; i++) {
        // The second argument is to get around an IE bug of returning absolute URLs
        var href = link.getAttribute('href', 2);
        // Only process internal site links.
        if (href.indexOf(jsTools.basePath) == -1) {
          continue;
        }
        // Get just the part of the path used by menus.
        href = jsTools.helpers.getPath(href);
        new DLDB(link, jsTools.query + 'dynamicload/js', href, $(jsTools.dynamicloadDefaults[id]));
      }
    }
  }
}

jsTools.behaviors['dynamicload'] = dynamicloadAutoAttach;

/**
 * A dynamicload object
 */
function DLDB(elt, uri, href, target) {
  var db = this;
  this.elt = elt;
  this.uri = uri;
  this.href = href;
  this.target = target;
  this.elt.onclick = function() {
    // Insert progressbar.
    db.progress = new progressBar('dynamicloadprogress');
    db.progress.setProgress(-1, 'Fetching page');
    db.progress.element.style.width = '50em';
    db.progress.element.style.height = '18px';
    db.cachedContent = db.target.innerHTML;
    while(db.target.firstChild) {
      db.target.removeChild(db.target.firstChild);
    }
    db.target.appendChild(db.progress.element);
    HTTPPost(db.uri, db.receive, db, {'href' : db.href, 'target' : db.target.id});
    return false;
  }
}

/**
 * HTTP callback function.
 */
DLDB.prototype.receive = function(string, xmlhttp, dldb) {
  if (xmlhttp.status != 200) {
    dldb.target.innerHTML = dldb.cachedContent;
    return alert('An HTTP error '+ xmlhttp.status +' occured.\n'+ dldb.uri);
  }
  dldb.progress = null;
  // Parse back result
  var item = parseJson(string);
  if (item['result'] && item['content']) {
    dldb.target.innerHTML = item['content'];
  }
  else {
    window.location = jsTools.basePath + jsTools.query + dldb.href;
  }
}

