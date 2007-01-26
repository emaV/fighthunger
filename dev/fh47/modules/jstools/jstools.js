// $Id: jstools.js,v 1.1.2.3 2006/06/06 03:29:35 nedjo Exp $

/**
 * A set of helper functions, beyond the methods declared in drupal.js.
 *
 * Cookie handling adapted from tab-pane library by Erik Arvidsson of webfx.
 * Debugging methods, event attaching/detaching, and srcElement detection adapted
 * from wForms library by Cédric Savarese.
 */

function jsToolsHelpers() {};

/**
 * Return the path portion of an href.
 */
jsToolsHelpers.prototype.getPath = function(href) {
  return href.substring(jsTools.basePath.length + jsTools.query.length, href.length);
}

/**
 * Set a cookie.
 */
jsToolsHelpers.prototype.setCookie = function(sName, sValue, nDays) {
  var expires = "";
  if (nDays) {
    var d = new Date();
    d.setTime(d.getTime() + nDays * 24 * 60 * 60 * 1000);
    expires = '; expires=' + d.toGMTString();
  }

  document.cookie = sName + '=' + sValue + expires + '; path=/';
};

/**
 * Set a previously set cookie.
 */
jsToolsHelpers.prototype.getCookie = function(sName) {
  var re = new RegExp("(\;|^)[^;]*(" + sName + ")\=([^;]*)(;|$)");
  var res = re.exec(document.cookie);
  return res != null ? res[3] : null;
};

/**
 * Unset a cookie.
 */
jsToolsHelpers.prototype.removeCookie = function(name) {
  setCookie(name, '', -1);
};

/**
 * Attach an event to an element.
 */
jsToolsHelpers.prototype.addEvent = function(obj, type, fn) {
  if (!obj) {
    return;
  }
  if (obj.attachEvent) {
    obj['e'+type+fn] = fn;
    obj[type+fn] = function(){obj['e'+type+fn](window.event);}
    obj.attachEvent('on' + type, obj[type+fn]);
  }
  else if (obj.addEventListener) {			
    obj.addEventListener(type, fn, false);
  }
  else {
    var originalHandler = obj['on' + type];
    if (originalHandler) {
      obj['on' + type] = function(e){originalHandler(e);fn(e);}; 
    }
    else {
      obj['on' + type] = fn; 
    } 
  }
}

/**
 * Remove an event from an element.
 */
jsToolsHelpers.prototype.removeEvent = function(obj, type, fn) {
  if (obj.detachEvent) {
    if(obj[type+fn]) {
      obj.detachEvent( 'on'+type, obj[type+fn] );
      obj[type+fn] = null;
    }
  }
  else if (obj.removeEventListener) {
    obj.removeEventListener( type, fn, false );
  }
  else {
    obj["on" + type] = null;
  }
}

/**
 * Returns an event's source element.
 */
jsToolsHelpers.prototype.getSourceElement = function(e) {	
  if (!e) e = window.event;	
  var srcE = e.target ? e.target : e.srcElement
  if (!srcE) return null;
  if (srcE.nodeType == 3) srcE = srcE.parentNode; // safari weirdness		
  if (srcE.tagName.toUpperCase()=='LABEL' && e.type=='click') { 
    // When clicking a label, firefox fires the input onclick event
    // but the label remains the source of the event. In Opera and IE 
    // the source of the event is the input element. Which is the 
    // expected behavior, I suppose.		
    if (srcE.getAttribute('for')) {
      srcE = document.getElementById(srcE.getAttribute('for'));
    }
  }
  return srcE;
}

/**
 * If a target element has not been set, return the document object.
 */
jsToolsHelpers.prototype.getTarget = function(elt) {
  return elt ? elt : document;
}

/**
 * Open a popup window.
 *
 * Note: this method is only roughed in and will need refining.
 */
jsToolsHelpers.prototype.openPopup = function(url, index, mw, mh) {
  if (!jsTools.newWindow[index].closed && jsTools.newWindow[index].location) {
    jsTools.newWindow[index].location.href = url;
  }
  else {
    var ox = mw;
    var oy = mh;
    if((ox >= screen.width) || (oy >= screen.height)){
      var ox = screen.width-150;
      var oy = screen.height-150;
      var winx = (screen.width / 2)-(ox / 2);
      var winy = (screen.height / 2)-(oy / 2);
      var use_scrollbars = 1;
    }
    else{
      var winx = (screen.width / 2)-(ox / 2);
      var winy = (screen.height / 2)-(oy / 2);
      var use_scrollbars = 0;
    }
    if (!index) {
      index = jsTools.newWindow.length;
    }
    jsTools.newWindow[index] = window.open(url, 'drupalNewWindow', 'height=' + oy + '-10,width=' + ox + ',top=' + winy + ',left=' + winx + ',scrollbars=' + useScrollbars + ',resizable');
    if (!jsTools.newWindow[index].opener) {
      jsTools.newWindow[index].opener = self;
    }
    return index;
  }
  if (window.focus) {
    newWindow.focus();
  }
  return newWindow;
}

/**
 * A set of effects. These 'vanilla' effects can be overridden by effects libraries.
 */
var jsToolsEffects = function() {};

/**
 * Hide or display an element.
 */
jsToolsEffects.prototype.collapse = function(node) {
  toggleClass(node, 'collapsed');
}

/**
 * Scroll to a given element's vertical page position.
 */
jsToolsEffects.prototype.scrollTo = function(node) {
  var pos = absolutePosition(node);
  window.scrollTo(0, pos.y);
}

/**
 * The jsTools object registers and calls behaviors and holds their associated properties.
 */
if (jsToolsHelpers && jsToolsEffects) {
  var jsTools = {
    debugLevel: 0,
    newWindow: [],
    behaviors: {},
    helpers: new jsToolsHelpers(),
    effects: new jsToolsEffects(),
    init: function() {
      jsTools.attachBehaviors();
      // We detect core behaviors after inital attachment because they already register themselves
      // as load events.
      jsTools.detectCoreBehaviors();
    },
    attachBehaviors: function(behaviors, elt) {
      if (behaviors) {
        for(i = 0; behaviorName = behaviors[i]; i ++) {
          if (jsTools.behaviors[behaviorName]) {
            jsTools.debug('jsTools/loaded ' + behaviorName);
            jsTools.behaviors[behaviorName](elt);
          }
        }
      }
      else {
        for(var behaviorName in jsTools.behaviors) {
          jsTools.debug('jsTools/loaded ' + behaviorName);
          jsTools.behaviors[behaviorName](elt);
        }
      }
    },
    detectCoreBehaviors: function() {
      var coreBehaviors = ['upload', 'autocomplete'];
      for (var i = 0; behaviorName = coreBehaviors[i]; i++) {
        if (eval('window.' + behaviorName + 'AutoAttach')) {
          jsTools.debug('jsTools/detected ' + behaviorName);
          jsTools.behaviors[behaviorName] = eval(behaviorName + 'AutoAttach');
        }
      }
    },
    behaviorInvoke: function(behaviorName, elt) {
      if (jsTools.behaviors[behaviorName]) {
        jsTools.debug('jsTools/invoked ' + behaviorName);
        jsTools.behaviors[behaviorName](elt);
      }
    },
    /**
     * Debug functions.
     */
    debug: function(txt) {
      // 1 = least importance, X = most important
      var msgLevel = arguments[1] || 10;

      if (jsTools.debugLevel > 0 && msgLevel >= jsTools.debugLevel) {
        if (!jsTools.debugOutput) {
          jsTools.initDebug();
        }
        if (jsTools.debugOutput) {
          jsTools.debugOutput.innerHTML += '<br />' + txt;
        }
      }
    },
    
    initDebug: function() {
      var output = document.getElementById('debugOutput');
      if (!output) {
        output = document.createElement('div');
        output.id = 'jsToolsDebugOutput';
        output.className = 'translucent';
        // Ensure page is fully loaded.
        if (document.body) {
          output.onmouseover = function() {
            removeClass(this, 'translucent');
          }
          output.onmouseout = function() {
            addClass(this, 'translucent');
          }
          jsTools.debugOutput = document.body.appendChild(output);
        }
      }
      if(jsTools.debugOutput) {
        jsTools.debugOutput.ondblclick = function() { this.innerHTML = ''; };
      }
    }
  };
}

// Killswitch for all jstools behaviors.
// jsTools.init calls all registered behaviors' attach methods.
if (isJsEnabled()) {
  addLoadEvent(jsTools.init);
}

/**
 * All functions from here to end are temporary fixes to issues in drupal.js.
 */

/**
 * Parse a JSON response.
 *
 * The result is either the JSON object, or an object with 'status' 0 and 'data' an error message.
 */
function parseJson(data) {
  if ((data.substring(0, 1) != '{') && (data.substring(0, 1) != '[')) {
    return { status: 0, data: data.length ? data : 'Unspecified error' };
  }
  return eval('(' + data + ');');
}

/**
 * Removes a class name from an element
 */
function removeClass(node, className) {
  if (!hasClass(node, className)) {
    return false;
  }
  // Replaces words surrounded with whitespace or at a string border with a space. Prevents multiple class names from being glued together.
  node.className = eregReplace('(^|\\s+)'+ className +'($|\\s+)', ' ', node.className);
  return true;
}
