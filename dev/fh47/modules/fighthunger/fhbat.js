if (isJsEnabled()) {
//  setTimeout('clicksMap()' , 1000);
  addLoadEvent(clicksMap);
}

function setStyle(element, styles, camelized) {
  //element = $(element);
  var elementStyle = element.style;
  for (var property in styles) {
    if (property == 'opacity') element.setOpacity(styles[property])
    else
      elementStyle[(property == 'float' || property == 'cssFloat') ?
        (elementStyle.styleFloat === undefined ? 'cssFloat' : 'styleFloat') :
        (camelized ? property : camelize(property))] = styles[property];
  }
  return element;
}

function camelize(property) {
  var parts = property.split('-'), len = parts.length;
  if (len == 1) return parts[0];

  var camelized = property.charAt(0) == '-'
    ? parts[0].charAt(0).toUpperCase() + parts[0].substring(1)
    : parts[0];

  for (var i = 1; i < len; i++)
    camelized += parts[i].charAt(0).toUpperCase() + parts[i].substring(1);
  return camelized;
}

function clicksMap() {
//  alert("Hello 1");

 // content = document.getElementById('fhbat_map');
  var content = $('fhbat_map');
  var button  = $('fhbat_button');

  uri = content.getAttribute("fh_clicks");
  cDB = new fh_cDB(uri);
  jsAC = new fh_jsAC(content, button, cDB);
  
  // redim map
//  $("map").style['width'] = content.clientWidth + 'px';

  jsAC.initialize();
}

/**
 * An AddClick
 */
function fh_jsAC(content, button, db) {
  var ac = this;

  this.clicks = new Array();
  this.delay  = 5000;  // delay in millisecond between showing clicks

  // status
  this.isIdle = true;
  this.isRefreshing = false;
  this.isMapping = false;
  this.isWaiting = true;
  
  // waiting message
  this.opacity = 0;
  this.swirl = null;
  this.statusMessage = null;
  
  // elements
  this.content = content;
  this.button  = button;
  this.fhGMarker = null;
  this.db = db;
};

fh_jsAC.prototype.swirlStatusMessage = function() {
  this.opacity++;
  this.opacity = (this.opacity) % 2;
  this.statusMessage.style["opacity"] = 1 - this.opacity / 2;
}

fh_jsAC.prototype.createStatusMessage = function() {
  new_statusMessage = document.createElement("div");
  new_statusMessage.setAttribute("id", "fhbat_StatusMessage");
  new_statusMessage.setAttribute("class", "button");
  new_statusMessage.innerHTML = '<p><b>Waiting&hellip;</b></p>';
  stylesCSS = {
    "padding"  : "10px 15px 0px",
    "position" : "relative",
    "bottom"   : "90px",
    "left"     : "10px"
  };
  setStyle(new_statusMessage, stylesCSS);
  new_statusMessage.style.display = "none";
  
  this.content.appendChild(new_statusMessage);
  this.statusMessage = document.getElementById('fhbat_StatusMessage');
}

fh_jsAC.prototype.setIdle = function (value) {
  
  if(this.isIdle != value) {

    txt = 'CHANGE: ';
    var theNewParagraph = document.createElement('h3');
    txt += 'isIdle: ' + this.isIdle + '#' + value ;
    txt += ( (map) ? " - habemus mappa" : " - no habemus" );
    
    if(map) {
//      txt += "MAP: " + map.Ta.width + " - " + content.clientWidth;
      if(value) {
        txt += ' # MOSTRA waiting!';
        this.opacity = 0;
        this.statusMessage.style.display = "inline";
        var self = this;
        this.swirl = setInterval(function() { self.swirlStatusMessage(); } , 500);
//        this.swirl = setInterval(this.swirlStatusMessage, 100);
      } else {
        txt += ' # NASCONDI waiting!';
        clearInterval(this.swirl);
        this.statusMessage.style.display = "none";
      }
    }
/*
    theNewParagraph.appendChild(document.createTextNode(txt));
    this.content.appendChild(theNewParagraph);
*/
  } else {
    txt = '';
  }
  this.isIdle = value;

}

/**
 * check the queue
 *   empty 
 *     -> load data
 *     -> show waiting message 
 *   not empty
 *     -> hide waiting message 
 *     -> shift the data and process  
 */
fh_jsAC.prototype.process = function () {

  if(this.clicks && this.clicks.length>0) {
    this.setIdle(false);
    click = this.clicks.shift();
    this.mapClick(click);
  } else {
    this.setIdle(true);
    this.refreshClicks();
  }
}

/**
 * Start the engine:
 * 1 - create the status message
 * 2 - start the interval queue check process  
 */
fh_jsAC.prototype.initialize = function () {

  this.db.owner = this;
  this.createStatusMessage();

  var self = this;
  setInterval(function() { self.process(); } , this.delay);
  
}

/**
 * Positions the suggestions popup and starts a search
 */
fh_jsAC.prototype.refreshClicks = function () {

  var myTime = new Date();
  
  if(!this.isRefreshing) {
    this.isRefreshing = true;
    this.db.getClicks(this.timestamp);
  }
}

/**
 * Fills the suggestion popup with any matches received
 */

fh_jsAC.prototype.mapClick = function (click) {

  lat = click['latitude'];
  lon = click['longitude'];

  // set time text message
  if( (click['delay'] / 3600)>1 ) {
    txtTimeAgo = 'more than 1 hour ago.';
  } else {
    min = Math.floor(click['delay'] / 60);
    if(min>=1) {
      txtTimeAgo = min + ((min>1) ? ' minutes' : ' minute') + ' ago.';
    } else {
      txtTimeAgo = click['delay'] + ( (click['delay']>1) ? ' seconds' : ' second') + ' ago.';
    }
  }
  // set location text message
  if( click['country_name'] ) {
    if( click['name'] ) {
      txtLocation = ' in ' + click['name'] + ', ' + click['country_name'];
    } else {
      txtLocation = ' in ' + click['country_name'];
    }
  } else {
    txtLocation = '';
  }
  
  txt  = 'A visitor' + txtLocation + '<br/>';
  txt += 'clicked to feed a child.' + '<br/>';
  txt += '<b>' + txtTimeAgo + '</b>';

/*
  txt += 'IP: ' + click['IP'] + '<br/>';
  txt += 'name: ' + click['name'] + '<br/>';
  txt += 'country_code: ' + click['country_code'] + '<br/>';
  txt += 'country_name: ' + click['country_name'] + '<br/>';
  txt += 'longitude: ' + click['longitude'] + '<br/>';
  txt += 'latitude: ' + click['latitude'] + '<br/>'; 
  txt += '<br/>' + click['IP'] + ', ' + click['data'] + ', ' + click['timestamp'] + '<br/>';
  txt += 'delay: ' + this.i + ' <b>' + click['delay'] + '</b><br/>';
  txt += click['delay'] / 3600 + ' <b>' + txtTimeAgo + '</b>';
*/
  
  url = '';
  tit = click['name'] + ' - ' + click['timestamp'];
  if(this.fhGMarker) {
    map.closeInfoWindow();
    map.removeOverlay(this.fhGMarker);
    this.fhGMarker = null;
  }
  this.fhGMarker = new GMarker(new GLatLng(lat,lon));
  map.addOverlay(this.fhGMarker);
  this.fhGMarker.openInfoWindowHtml(txt);
  
}

/**
 * Fills the suggestion popup with any matches received
 */
fh_jsAC.prototype.found = function (result) {

  // get clicks array
  newClicks = result['clicks'];

  if (newClicks) {
  
    startTime = result['timestamp_old'];
    endTime   = result['timestamp_new'];
    delay = 0;
    
    for(j=0; j < newClicks.length; j++) {
      timestamp = newClicks[j]['timestamp'];
      newClicks[j]['delay'] = endTime - timestamp + j * (this.delay / 1000) + 2;
      
      this.clicks.push(newClicks[j]);
/*
      var theNewParagraph = document.createElement('p');
      ztxt  = endTime + ' - ' + timestamp + ' - ' + newClicks[j]['delay'];
      var theTextOfTheParagraph = document.createTextNode('ZZZ: ' + j + ' -<b>' +ztxt + '</b> ' + this.clicks[j]['delay'] );
      theNewParagraph.appendChild(theTextOfTheParagraph);
      content.appendChild(theNewParagraph);
*/      
    }
  
  }
  this.isRefreshing = false;
}

/**
 * A clicks DataBase object
 */
function fh_cDB(uri) {
  this.uri   = uri;
  this.delay = 300;
  this.cache = {};
  this.timestamp = -1; 
}

/**
 * Performs a cached and delayed search
 */
fh_cDB.prototype.getClicks = function() {

  var db = this;
  this.timer = setTimeout(function() {
    db.transport = HTTPGet(db.uri + '/' + encodeURIComponent(db.timestamp) , db.receive, db);
  }, this.delay);

}

/**
 * HTTP callback function. Passes suggestions to the autocomplete object
 */
fh_cDB.prototype.receive = function(string, xmlhttp, acdb) {
  // Note: Safari returns 'undefined' status if the request returns no data.
  if (xmlhttp.status != 200 && typeof xmlhttp.status != 'undefined') {
    return alert('An HTTP error '+ xmlhttp.status +' occured.\n'+ acdb.uri);
  }

  // Parse back result
  var result = parseJson(string);
  acdb.timestamp = result['timestamp_new'];
  acdb.owner.found(result);
}
