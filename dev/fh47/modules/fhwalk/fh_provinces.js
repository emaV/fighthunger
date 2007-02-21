if (isJsEnabled()) {
//  addLoadEvent(clickInfoAutoAttach);
  addLoadEvent(clickCountryProvince);
}

function clickCountryProvince() {
  province = document.getElementById('edit-location-province');

  country  = document.getElementById('edit-location-country');
  uri = country.getAttribute("fh_autocomplete_path");

  acdb = new fh_ACDB(uri);

  ac = new fh_jsAC(country, province, acdb);
//  if(province.selectedIndex<0)  ac.populateProvince();
  if(country.selectedIndex>0 && province.selectedIndex<0)  ac.populateProvince();
}

/**
 * An AutoComplete object
 */
function fh_jsAC(country, province, db) {
  var ac = this;
  this.country  = country;
  this.province = province;
  this.db = db;
  this.country.onchange = function () { ac.populateProvince(); };
};

/**
 * Positions the suggestions popup and starts a search
 */
fh_jsAC.prototype.populateProvince = function () {

  // call DB object
  this.db.owner = this;
  this.db.search(this.country.options[this.country.selectedIndex].value);
}

/**
 * Fills the suggestion popup with any matches received
 */
fh_jsAC.prototype.found = function (matches) {

  j=0;
  var ac = this;
  
	// empty existing items
	for (i = province.options.length; i >= 0; i--) {
		province.options[i] = null; 
	}

  // Add received Options
  for (key in matches) {
    this.province.options[j] = new Option(matches[key], key);
    j++;
  }
  div_province = province.parentNode;
  if(j==0) {
//    div_province.setAttribute("style", "display:none;");
// fix for IE http://www.webmasterworld.com/forum91/5025.htm
    div_province.style.display = "none";
  } else {
//    div_province.setAttribute("style", "display:inline;");
    div_province.style.display = "inline";
    province.selectedIndex = 0;
  } 
  
}

/**
 * An AutoComplete DataBase object
 */
function fh_ACDB(uri) {
  this.uri = uri;
  this.delay = 300;
  this.cache = {};
}

/**
 * Performs a cached and delayed search
 */
fh_ACDB.prototype.search = function(searchString) {

  this.searchString = searchString;
  if (this.cache[searchString]) {
    return this.owner.found(this.cache[searchString]);
  }
  if (this.timer) {
    clearTimeout(this.timer);
  }
  var db = this;

  this.timer = setTimeout(function() {
    addClass(db.owner.province, 'throbbing');
    db.transport = HTTPGet(db.uri +'/'+ encodeURIComponent(searchString), db.receive, db);
  }, this.delay);
}

/**
 * HTTP callback function. Passes suggestions to the autocomplete object
 */
fh_ACDB.prototype.receive = function(string, xmlhttp, acdb) {
  // Note: Safari returns 'undefined' status if the request returns no data.
  if (xmlhttp.status != 200 && typeof xmlhttp.status != 'undefined') {
    removeClass(acdb.owner.province, 'throbbing');
    return alert('An HTTP error '+ xmlhttp.status +' occured.\n'+ acdb.uri);
  }

  // Parse back result
  var matches = parseJson(string);
  if (typeof matches['status'] == 'undefined' || matches['status'] != 0) {
    acdb.cache[acdb.searchString] = matches;
    acdb.owner.found(matches);
  }
}

/**
 * Cancels the current autocomplete request
 */
fh_ACDB.prototype.cancel = function() {
  if (this.owner) removeClass(this.owner.province, 'throbbing');
  if (this.timer) clearTimeout(this.timer);
  if (this.transport) {
    this.transport.onreadystatechange = function() {};
    this.transport.abort();
  }
}
