
function changeMinisterName(select){
//grab the name
	var countries = new Array();
	
	countries[1] = "George W. Bush &lt;george@whitehouse.gov&gt;";
	countries[2] = "Paul Martin &lt;paul@canada.gov&gt;";
	
    var name = select.value;

    //display the name
	document.getElementById('minister_name').innerHTML = "Dear " + countries[name] + ",";
}
