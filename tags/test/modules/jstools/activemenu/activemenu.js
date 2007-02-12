// $Id: activemenu.js,v 1.8.2.4 2006/05/30 04:30:13 nedjo Exp $

function activemenuAutoAttach() {
  var container = activemenuCreateContainer();
  // The divs supported. Each can designate a different uri.
  var menus = {'block-user-1': 'activemenu/js/'};
  // Find and add all menu-module generated blocks
  // 
  var divs = document.getElementsByTagName('div');
  for (var i = 0; div = divs[i]; i++) {
    if (div && hasClass(div, 'block-menu')) {
      // Parse out the menu id.
      var menuId = parseInt(div.getAttribute('id').replace(/[\D]*/,''));
      menus['block-menu-' + menuId] = 'activemenu/js/';
    }
    // Find all divs identified with the 'activemenu' class.
    // These divs should have ids in the form of the activemenu uri that
    // data should be posted to, with dashes (-) in the place of forward
    // slashed (/). For example, if the address is examplemodule/js,
    // the element would be: <div id="examplemenu-js" class="activemenu"></div>
    if (div && hasClass(div, 'activemenu')) {
      var menuId = div.getAttribute('id');
      menus[menuId] = menuId.replace(/-/g, '/');
    }
  }

  for (var menu in menus) {
    var menuDiv = document.getElementById(menu);

    if (menuDiv) {
      var uri = menus[menu];
      var lis = menuDiv.getElementsByTagName('li');
      for (var i = 0; li = lis[i]; i++) {
        if (li && hasClass(li, 'collapsed') || hasClass(li, 'expanded')) {
          var pos = absolutePosition(li);
          var div = document.createElement('div');
          div.className = 'activemenuPointer';
          div.style.left = (pos.x - 20) + 'px';
          div.style.top = pos.y + 'px';
          div.owner = li;
          if (hasClass(li, 'collapsed') && !li.getElementsByTagName('ul').length) {
            // The second argument is to get around an IE bug of returning absolute URLs
            var href = li.firstChild.getAttribute('href', 2);
            // Get just the part of the path used by menus.
            href = jsTools.helpers.getPath(href);
            jsTools.debug('activemenu/created activemenu for href ' + href);
            new jsAM(div, jsTools.basePath + jsTools.query + uri, href);
          }
          else {
            div.onclick = function() {
              toggleClass(this.owner, 'collapsed');
              toggleClass(this.owner, 'expanded');
              activemenuAutoAttach();
            }
          }
          container.appendChild(div);
        }
      }
    }
  }
}

// Register the behavior.
jsTools.behaviors['activemenu'] = activemenuAutoAttach;

function activemenuCreateContainer() {
  if (document.getElementById('activemenuContainer')) {
    // Remove all children from element
    var container = document.getElementById('activemenuContainer');
    while (container.firstChild) {
      container.removeChild(container.firstChild);
    }
  }
  else {
    var container = document.createElement('div');
    container.setAttribute('id', 'activemenuContainer');
    document.getElementsByTagName('body')[0].appendChild(container);
  }
  jsTools.debug('activemenu/created container');
  return container;
}

/**
 * An activemenu object
 */
function jsAM(elt, uri, href) {
  var db = this;
  this.elt= elt;
  this.uri = uri;
  this.href = href;
  this.elt.onclick = function() {
    jsTools.debug('activemenu/useraction: clicked ' + db.href);
    var ul = document.createElement('ul');
    var li = document.createElement('li');
    li.appendChild(document.createTextNode('loading'));
    ul.appendChild(li);
    db.elt.owner.appendChild(ul);
    db.target = ul;
    removeClass(db.elt.owner, 'collapsed');
    addClass(db.elt.owner, 'expanded');
    HTTPPost(db.uri, db.receive, db, {'href' : db.href});
    jsTools.debug('activemenu/HTTPPost issued to ' + db.uri);
  }
}

/**
 * HTTP callback function.
 */
jsAM.prototype.receive = function(string, xmlhttp, jsam) {
  jsTools.debug('activemenu/HTTPPost response received: ' + string);
  if (xmlhttp.status != 200) {
    jsTools.debug('activemenu/HTTP error '+ xmlhttp.status +'.<br />'+ jsam.uri);
    return;
  }
  // Parse back result
  var items = parseJson(string);
  if (typeof items['status'] == 'undefined' || items['status'] != 0) {
    var ul = document.createElement('ul');
    for (key in items) {
      // IE seems to add an undefined item at the beginning of the array
      if (typeof(items[key].path) != 'undefined') {
        var li = document.createElement('li');
        li.className = items[key].children ? 'collapsed' : 'leaf';
        var a = document.createElement('a');
        a.setAttribute('href', jsTools.basePath + jsTools.query + items[key].path);
        a.appendChild(document.createTextNode(items[key].title));
        li.appendChild(a);
        ul.appendChild(li);
      }
    }
    jsam.elt.owner.replaceChild(ul, jsam.target);
    activemenuAutoAttach();
  }
}

