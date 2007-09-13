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

  content = document.getElementById('fhbat_map');
  button  = document.getElementById('fhbat_button');

  var theNewParagraph = document.createElement('p');
  var theTextOfTheParagraph = document.createTextNode('Content width: ' + content.clientWidth);
  theNewParagraph.appendChild(theTextOfTheParagraph);
  content.appendChild(theNewParagraph);

  uri = content.getAttribute("fh_clicks");
  cDB = new fh_cDB(uri);
  jsAC = new fh_jsAC(content, button, cDB);
  
//  alert("Hello 2");

  jsAC.initialize();
/*
  var statusMessage = document.createElement("div");
  statusMessage.setAttribute("id", "qwer");
  statusMessage.setAttribute("class", "button");

  statusMessage.innerHTML = '<p><b>Waiting&hellip;</b></p>';
/*  
statusMessage.style["color"] = "#006699";
statusMessage.style["backgroundColor"] = "#ff6600";

  
  stylesCSS = {
    "padding": "10px 15px 0px",
    "position": "absolute",
    "top"  : "50px",
    "left": String(Math.ceil((content.clientWidth - statusMessage.clientWidth) / 2)) + "px"
  };

setStyle(statusMessage, stylesCSS);

/*
    "opacity": "0"
  with (statusMessage.style) {
//    color = '#006699';
//    'background-color' = '#006699';
  };
  */
/*
   with (statusMessage.style) {
    "position" = "absolute";
    "opacity"= "0";
    "border-width"= "1px";
    "border-style"= "solid";
    "border-color"= "#ff6600";
    "padding"="2px 15px";
    "background-color"= "#ffffff";
    "color"= "#006699";
    "bottom"= "52px";
  });

  content.appendChild(statusMessage);
*/ 
//    "left": String(Math.ceil((content.clientWidth - statusMessage.getWidth()) / 2)) + "px"
  
//  content.appendChild(statusMessage);

//  alert("Hello 3");

//  if(province.selectedIndex<0)  ac.populateProvince();
//  if(country.selectedIndex>0 && province.selectedIndex<0)  ac.populateProvince();

//  map.createMap();
}

/**
 * An AddClick
 */
function fh_jsAC(content, button, db) {
  var ac = this;

  this.clicks = new Array();
  this.delay  = 5000;  // delay in millisecond between showing clicks

  this.isIdle = true;
  
  this.isRefreshing = false;
  this.isMapping = false;
  
  this.isWaiting = true;
  this.content = content;
  this.button  = button;
  this.fhGMarker = null;
  this.db = db;
  this.button.onclick = function () { ac.process(); };
  
  this.statusMessage = null;
};

fh_jsAC.prototype.createStatusMessage = function() {
  this.statusMessage = document.createElement("div");
  this.statusMessage.setAttribute("id", "fhbat_StatusMessage");
  this.statusMessage.setAttribute("class", "button");
  this.statusMessage.innerHTML = '<p><b>Waiting&hellip;</b></p>';
  stylesCSS = {
    "padding": "10px 15px 0px",
    "position": "absolute",
    "top"  : "50px",
    "left" : String(Math.ceil((this.content.clientWidth - this.statusMessage.clientWidth) / 2)) + "px"
  };
  setStyle(this.statusMessage, stylesCSS);
//  this.statusMessage = statusMessageN;
}

fh_jsAC.prototype.setIdle = function (value) {
  
  if(this.isIdle != value) {


    var theNewParagraph = document.createElement('h3');
    var theTextOfTheParagraph = document.createTextNode('isIdle: ' + this.isIdle);

    if(map) theTextOfTheParagraph += " - habemus mappa";

    theNewParagraph.appendChild(theTextOfTheParagraph);
    this.content.appendChild(theNewParagraph);
  
    this.content.appendChild(this.statusMessage);
  }
  this.isIdle = value;
//  map_div = document.getElementById('map'); 
//  map_div.appendChild(this.statusMessage);


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
    
//    alert('queue has now: ' + this.clicks.length);

    // map clicks
    click = this.clicks.shift();
    this.mapClick(click);
    
  } else {
    this.setIdle(true);
    

//    alert('queue empty');
    
    // reload the queue
    this.refreshClicks();
    
  }

}


/**
 * Start the engine:
 * 1 - create the status message
 * 2 - start the interval queue check process  
 */
fh_jsAC.prototype.initialize = function () {
  this.createStatusMessage();

  this.db.owner = this;

  self = this;
  setInterval(function() { self.process(); } , this.delay);
  
//  this.refreshClicks();
  
//  this.setIdle(true);
}

/**
 * Positions the suggestions popup and starts a search
 */
fh_jsAC.prototype.refreshClicks = function () {


  var myTime = new Date();
  
  // call DB object
//  this.db.owner = this;
//  alert("refreshClicks: Refreshing? " + this.isRefreshing);
  
  if(!this.isRefreshing) {
    this.isRefreshing = true;
    this.db.getClicks(this.timestamp);
  }
  
/*  
  var theNewParagraph = document.createElement('p');
  var theTextOfTheParagraph = document.createTextNode('Some content at ' + myTime.toString() + ': refreshClicks # ' + txt);
  theNewParagraph.appendChild(theTextOfTheParagraph);
  content.appendChild(theNewParagraph);
*/ 
}

/**
 * Fills the suggestion popup with any matches received
 */

fh_jsAC.prototype.mapClick = function (click) {

//  alert('Click is ' + click['name']);

  //delay = click['delay'];

  lat = click['latitude'];
  lon = click['longitude'];
// seet text messagge with time
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
  txt  = 'A visitor in ' + click['name'] + ', ' + click['country_name'] + ' clicked to feed a child.' + '<br/>';
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
//  this.fhGmarker = createGMarker(new GLatLng(lat,lon),txt,url,tit,'');
  this.fhGMarker = new GMarker(new GLatLng(lat,lon));
  map.addOverlay(this.fhGMarker);
  this.fhGMarker.openInfoWindowHtml(txt);
//  map.panTo(new GLatLon(lat, lon));
  
  var theNewParagraph = document.createElement('p');
//  var theTextOfTheParagraph = document.createTextNode('IP clicks: ' + click['latitude'] + '<br/>' + click['timestamp']);
  var theTextOfTheParagraph = document.createTextNode('mapClick: ' + this.i);
  theNewParagraph.appendChild(theTextOfTheParagraph);
  content.appendChild(theNewParagraph);
/*
  pane = map.getPane(G_MAP_MAP_PANE);
  pane.appendChild(theNewParagraph);
*/
  
}

/**
 * Fills the suggestion popup with any matches received
 */
fh_jsAC.prototype.found = function (result) {

  // get clicks array
  newClicks = result['clicks'];

  
  if (newClicks) {
  
//    alert("found: " + newClicks.length );
  
  
    startTime = result['oldtimestamp'];
    endTime   = result['timestamp'];
    delay = 0;
    
    for(j=0; j < newClicks.length; j++) {
      timestamp = newClicks[j]['timestamp'];
      newClicks[j]['delay'] = endTime - timestamp + j * (this.delay / 1000);
      
      this.clicks.push(newClicks[j]);

//      var self = this;
//      setTimeout(function() { self.mapClick(); } , delay);

  var theNewParagraph = document.createElement('p');
  ztxt  = endTime + ' - ' + timestamp + ' - ' + delay;
  ztxt += ' <i>' + this.clicks + '</i>';
  
//  var theTextOfTheParagraph = document.createTextNode('IP clicks: ' + click['latitude'] + '<br/>' + click['timestamp']);
  var theTextOfTheParagraph = document.createTextNode('ZZZ: ' + j + ' -<b>' +ztxt + '</b> ' + this.clicks[j]['delay'] );
  theNewParagraph.appendChild(theTextOfTheParagraph);
  content.appendChild(theNewParagraph);
      
    }
  
  } else {
//    alert("Hello no clicks found.");
  }
  this.isRefreshing = false;

}

/**
 * A clicks DataBase object
 */
function fh_cDB(uri) {
  this.uri = uri;
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

//  alert("Run getClicks: " + db.timestamp + ' # ' + this.owner.isRefreshing);
  
//  db.receive()
}


/**
 * HTTP callback function. Passes suggestions to the autocomplete object
 */
fh_cDB.prototype.receive = function(string, xmlhttp, acdb) {
  // Note: Safari returns 'undefined' status if the request returns no data.
  if (xmlhttp.status != 200 && typeof xmlhttp.status != 'undefined') {
    return alert('An HTTP error '+ xmlhttp.status +' occured.\n'+ acdb.uri);
  }

//  alert("Hello receive: " + string);


  // Parse back result
  var result = parseJson(string);
  acdb.timestamp = result['timestamp'];
  
//  if (typeof matches['status'] == 'undefined' || matches['status'] != 0) {
//    acdb.cache[acdb.searchString] = matches;
    acdb.owner.found(result);
//  }
}


function dump(arr,level) {
  var dumped_text = "";
  if(!level) level = 0;
  
  //The padding given at the beginning of the line.
  var level_padding = "";
  for(var j=0;j<level+1;j++) level_padding += " ";
  
  if(typeof(arr) == 'object') { //Array/Hashes/Objects
    for(var i=0;i<arr.length;i++) {
      var value = arr[i];
      
      if(typeof(value) == 'object') { //If it is an array,
        dumped_text += level_padding + "'" + i + "' ==> ";
        dumped_text += "{\n" + dump(value,level+1) + level_padding + "}\n";
      } else {
        dumped_text += level_padding + "'" + i + "' ==> \"" + value + "\"\n";
      }
    }
  } else { //Stings/Chars/Numbers etc.
    dumped_text = "===>"+arr+"<===("+typeof(arr)+")\n";
  }
  return dumped_text;
}
