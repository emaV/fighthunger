if (isJsEnabled()) {
//  addLoadEvent(clickInfoAutoAttach);
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

  uri = content.getAttribute("fh_clicks");
  
  cDB = new fh_cDB(uri);
  jsAC = new fh_jsAC(content, button, cDB);
  
  jsAC.initialize();
//  alert("Hello 2");

  var theNewParagraph = document.createElement('p');
  var theTextOfTheParagraph = document.createTextNode('Content width: ' + content.clientWidth);
  theNewParagraph.appendChild(theTextOfTheParagraph);
  content.appendChild(theNewParagraph);
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
//  var i = 0;
  this.i = 0;
  this.clicks = null;
  this.isIdle = true;
  this.isWaiting = true;
  this.content = content;
  this.button  = button;
  this.fhGMarker = null;
  this.db = db;
  this.button.onclick = function () { ac.refreshClicks(); };
  
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
  
  
/*  
  pane = map.getPane(G_MAP_MAP_PANE);
  pane.appendChild(this.statusMessage);
*/  

}

fh_jsAC.prototype.setIdle = function (value) {
  this.isIdle = true;
  var theNewParagraph = document.createElement('h3');
  var theTextOfTheParagraph = document.createTextNode('isIdle now');
  theNewParagraph.appendChild(theTextOfTheParagraph);
  this.content.appendChild(theNewParagraph);
/*  
  pane = map.getPane(G_MAP_MAP_PANE);
  pane.appendChild(theNewParagraph);
*/
  this.content.appendChild(this.statusMessage);
  
  map_div = document.getElementById('map'); 
  map_div.appendChild(this.statusMessage);

}

fh_jsAC.prototype.initialize = function () {
  this.createStatusMessage();
  this.refreshClicks();
//  this.setIdle(true);
}


/**
 * Positions the suggestions popup and starts a search
 */
fh_jsAC.prototype.refreshClicks = function () {

  var myTime = new Date();
  
  // call DB object
  this.db.owner = this;
  this.db.getClicks(this.timestamp);
  
  var theNewParagraph = document.createElement('p');
  var theTextOfTheParagraph = document.createTextNode('Some content at ' + myTime.toString() + ': refreshClicks');
  theNewParagraph.appendChild(theTextOfTheParagraph);
  content.appendChild(theNewParagraph);
}

/**
 * Fills the suggestion popup with any matches received
 */

fh_jsAC.prototype.mapClick = function () {

// alert('mapClick: ' + this.i);


  click = this.clicks[this.i];
  //delay = click['delay'];

  lat = click['latitude'];
  lon = click['longitude'];
// seet text messagg with time
  if( (click['delay'] / 3600)>1 ) {
    txtTimeAgo = 'more than 1 hour ago.';
  } else {
    min = Math.floor(click['delay'] / 60);
    if(min>=1) {
      txtTimeAgo = min + ((min>1) ? ' minutes' : ' minute') + ' ago.';
    } else {
      txtTimeAgo = click['delay'] + ((delay>1) ? ' seconds' : ' second') + ' ago.';
    }
  }
  txt = 'A visitor in ' + click['name'] + ', ' + click['contry_name'] + ' clicked to feed a child.';
  txt += '<br/>' + click['IP'] + ', ' + click['data'] + ', ' + click['timestamp'] + '<br/>';
  txt += 'delay: ' + this.i + ' <b>' + click['delay'] + '</b><br/>';
  txt += click['delay'] / 3600 + ' <b>' + txtTimeAgo + '</b>';
  
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
  
  this.i = this.i + 1;

  if(this.i == this.clicks.length) {
    this.setIdle(true);
  }
  
}

/**
 * Fills the suggestion popup with any matches received
 */
fh_jsAC.prototype.found = function (result) {

  // get clicks array
  this.clicks = result['clicks'];
  this.i = 0;
  
  if (this.clicks) {

    var out = "Hello foundz " + this.clicks.length + " clicks";
//  map.panTo(new GLatLon(0, 90));

/*
  lat = 0;
  lon = 0;
  txt = "Hello foundz " + this.clicks.length + " clicks";
  url = '';
  tit = 'Beeeep!';
  new_fhGmarker = createGMarker(new GLatLng(lat,lon),txt,url,tit,'');
  map.addOverlay(this.fhGmarker);
*/

 
    startTime = result['oldtimestamp'];
    endTime   = result['timestamp'];
    delay = 0;
    
    for(j=0; j<this.clicks.length; j++) {
      timestamp = this.clicks[j]['timestamp'];
      delay += 3000;
//      timeout = timestamp - startTime;
//      delay = (timeout>delay) ? timeout : delay;
  //    setTimeout(mapClick(click), click['timestamp']-startTime);
      var self = this;
      setTimeout(function() { self.mapClick(); } , delay);
      this.clicks[j]['delay'] = endTime - timestamp + (delay / 1000); 

  var theNewParagraph = document.createElement('p');
  ztxt = endTime + ' - ' + timestamp + ' - ' + delay;
//  var theTextOfTheParagraph = document.createTextNode('IP clicks: ' + click['latitude'] + '<br/>' + click['timestamp']);
  var theTextOfTheParagraph = document.createTextNode('ZZZ: ' + j + ' -<b>' +ztxt + '</b> ' + this.clicks[j]['delay'] );
  theNewParagraph.appendChild(theTextOfTheParagraph);
  content.appendChild(theNewParagraph);
      
    }
  
  } else {
    alert("Hello no clicks found.");
  }

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

  alert("Hello getClicks: " + db.timestamp);
  
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

  alert("Hello receive: " + string);


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
