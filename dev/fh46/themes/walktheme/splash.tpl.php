<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Fight Hunger - Walk the World</title>
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<script type="text/javascript"> </script><!-- FOUC hack -->
	<?php print $head ?>
			<style type="text/css" media="all">@import "themes/walktheme/splash.css";</style>
</head>
<body>

<div id="page">

	<div id="header">
		<img src="themes/walktheme/images/splash_logo_wtw.png" alt="Walk the World" width="152" height="86" border="0" class="right" />
		<h1><em>Fight Hunger:</em> Walk the World</h1>
		<p class="small">On 12 June, 200,000 people walked the world to end child hunger.  Join our global fight by entering your email address below to feed a child -- then see our photos and stories inside!</p>
	</div>
	<div class="clear">&nbsp;</div>

	<div class="lt_bl"><div class="lt_br"><div class="lt_tl"><div class="lt_tr"> 
		<div id="splash">
			<div id="splashpic"><img src="themes/walktheme/images/splash_photo.png" width="172" height="172" border="0" />
                <p style="font-size: .9em;"><?php $walkers  = variable_get('walkers', '0'); print($walkers); ?> others have clicked.<br />
                   <strong><?php $walkers  = variable_get('walkers', '0'); print($walkers); ?> children will be fed.</strong></p>
			</div><!-- close splashpic -->
			<div id="splashform">
<p>When you enter your email address below, our sponsors<span class="orange">*</span> will donate the cost of feeding one child for one day to the U.N. World Food Programme's Global School Feeding program.</p>
                <!-- form goes here -->
                <form action="virtual/signup" method="POST">

<table width="390" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="formleft">My Email Address:</td>
		<td class="formright"><input type="text" maxlength="50" class="form-text" name="edit[mail]" id="edit-mail" size="30" value="" /></td>
	</tr>
	<tr>
		<td class="formleft">My Home Country:</td>
		<td class="formright"><select name="edit[c]" id="edit-c"><option selected="selected" value="--">(choose your home country)</option><option value="AF">Afghanistan</option><option value="AL">Albania</option><option value="AG">Algeria</option><option value="AN">Andorra</option><option value="AO">Angola</option><option value="AV">Anguilla</option><option value="AC">Antigua And Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AA">Aruba</option><option value="AT">Ashmore And Cartier Islands</option><option value="AS">Australia</option><option value="AU">Austria</option><option value="AJ">Azerbaijan</option><option value="BF">Bahamas, The</option><option value="BA">Bahrain</option><option value="BG">Bangladesh</option><option value="BB">Barbados</option><option value="BS">Bassas Da India</option><option value="BO">Belarus</option><option value="BE">Belgium</option><option value="BH">Belize</option><option value="BN">Benin</option><option value="BD">Bermuda</option><option value="BT">Bhutan</option><option value="BL">Bolivia</option><option value="BK">Bosnia And Herzegovina</option><option value="BC">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="VI">British Virgin Islands</option><option value="BX">Brunei</option><option value="BU">Bulgaria</option><option value="UV">Burkina Faso</option><option value="BM">Burma</option><option value="BY">Burundi</option><option value="CB">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="CJ">Cayman Islands</option><option value="CT">Central African Republic</option><option value="CD">Chad</option><option value="CI">Chile</option><option value="CH">China</option><option value="KT">Christmas Island</option><option value="IP">Clipperton Island</option><option value="CK">Cocos Keeling Islands</option><option value="CO">Colombia</option><option value="CN">Comoros</option><option value="CF">Congo</option><option value="CG">Congo, Democratic Republic Of The</option><option value="CW">Cook Islands</option><option value="CR">Coral Sea Islands</option><option value="CS">Costa Rica</option><option value="IV">Cte D&#039;ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CY">Cyprus</option><option value="EZ">Czech Republic</option><option value="DA">Denmark</option><option value="DJ">Djibouti</option><option value="DO">Dominica</option><option value="DR">Dominican Republic</option><option value="TT">East Timor</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="ES">El Salvador</option><option value="EK">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EN">Estonia</option><option value="ET">Ethiopia</option><option value="EU">Europa Island</option><option value="FK">Falkland Islands Islas Malvinas</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="FG">French Guiana</option><option value="FP">French Polynesia</option><option value="FS">French Southern And Antarctic Lands</option><option value="GB">Gabon</option><option value="GA">Gambia, The</option><option value="GZ">Gaza Strip</option><option value="GG">Georgia</option><option value="GM">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GO">Glorioso Islands</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GJ">Grenada</option><option value="GP">Guadeloupe</option><option value="GT">Guatemala</option><option value="GK">Guernsey</option><option value="GV">Guinea</option><option value="PU">Guinea-bissau</option><option value="GY">Guyana</option><option value="HA">Haiti</option><option value="HM">Heard Island And Mcdonald Islands</option><option value="HO">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IC">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran</option><option value="IZ">Iraq</option><option value="EI">Ireland</option><option value="IM">Isle Of Man</option><option value="IS">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JN">Jan Mayen</option><option value="JA">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="JU">Juan De Nova Island</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KR">Kiribati</option><option value="KU">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Laos</option><option value="LG">Latvia</option><option value="LE">Lebanon</option><option value="LT">Lesotho</option><option value="LI">Liberia</option><option value="LY">Libya</option><option value="LS">Liechtenstein</option><option value="LH">Lithuania</option><option value="LU">Luxembourg</option><option value="MC">Macau</option><option value="MK">Macedonia, The Former Yugoslav Republic Of</option><option value="MA">Madagascar</option><option value="MI">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="RM">Marshall Islands</option><option value="MB">Martinique</option><option value="MR">Mauritania</option><option value="MP">Mauritius</option><option value="MF">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia, Federated States Of</option><option value="MD">Moldova</option><option value="MN">Monaco</option><option value="MG">Mongolia</option><option value="MH">Montserrat</option><option value="MO">Morocco</option><option value="MZ">Mozambique</option><option value="WA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="NT">Netherlands Antilles</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NU">Nicaragua</option><option value="NG">Niger</option><option value="NI">Nigeria</option><option value="NE">Niue</option><option value="NM">No Man&#039;s Land</option><option value="NF">Norfolk Island</option><option value="KN">North Korea</option><option value="NO">Norway</option><option value="OS">Oceans</option><option value="MU">Oman</option><option value="PK">Pakistan</option><option value="PS">Palau</option><option value="PM">Panama</option><option value="PP">Papua New Guinea</option><option value="PF">Paracel Islands</option><option value="PA">Paraguay</option><option value="PE">Peru</option><option value="RP">Philippines</option><option value="PC">Pitcairn Islands</option><option value="PL">Poland</option><option value="PO">Portugal</option><option value="QA">Qatar</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RS">Russia</option><option value="RW">Rwanda</option><option value="SH">Saint Helena</option><option value="SC">Saint Kitts And Nevis</option><option value="ST">Saint Lucia</option><option value="SB">Saint Pierre And Miquelon</option><option value="VC">Saint Vincent And The Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="TP">Sao Tome And Principe</option><option value="SA">Saudi Arabia</option><option value="SG">Senegal</option><option value="YI">Serbia And Montenegro</option><option value="SE">Seychelles</option><option value="SL">Sierra Leone</option><option value="SN">Singapore</option><option value="LO">Slovakia</option><option value="SI">Slovenia</option><option value="BP">Solomon Islands</option><option value="SO">Somalia</option><option value="SF">South Africa</option><option value="SX">South Georgia And The South Sandwich Islands</option><option value="KS">South Korea</option><option value="SP">Spain</option><option value="PG">Spratly Islands</option><option value="CE">Sri Lanka</option><option value="SU">Sudan</option><option value="NS">Suriname</option><option value="SV">Svalbard</option><option value="WZ">Swaziland</option><option value="SW">Sweden</option><option value="SZ">Switzerland</option><option value="SY">Syria</option><option value="TW">Taiwan</option><option value="TI">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TO">Togo</option><option value="TL">Tokelau</option><option value="TN">Tonga</option><option value="TD">Trinidad And Tobago</option><option value="TE">Tromelin Island</option><option value="TS">Tunisia</option><option value="TU">Turkey</option><option value="TX">Turkmenistan</option><option value="TK">Turks And Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UP">Ukraine</option><option value="UF">Undersea Features</option><option value="AE">United Arab Emirates</option><option value="UK">United Kingdom</option><option value="US">United States</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="NH">Vanuatu</option><option value="VT">Vatican City</option><option value="VE">Venezuela</option><option value="VM">Vietnam</option><option value="VQ">Virgin Islands</option><option value="WF">Wallis And Futuna</option><option value="WE">West Bank</option><option value="WI">Western Sahara</option><option value="YM">Yemen</option><option value="ZA">Zambia</option><option value="ZI">Zimbabwe</option></select></td>
	</tr>
	<tr>
		<td class="formleft">&nbsp;</td>
		<td class="formright"><input type="image" src="themes/walktheme/images/splash_btn_orange.png" name="op" value="submit" alt="submit" id="splash-submit" /></td>
	</tr>
	<tr>
		<td class="formleft">&nbsp;</td>
		<td class="formright"><input type="hidden" name="edit[optout]" value="0" /> <label class="option"><input type="checkbox" class="form-checkbox" name="edit[optout]" id="edit-optout" value="1" /> I do not wish to receive occasional email updates.</label></td>
	</tr>
</table>
  
</form>
                <!-- end form -->
			
			</div><!-- close splashform -->
			<br clear="all" />

    	</div><!-- close splash -->
	</div></div></div></div>
	<div class="clear">&nbsp;</div>
	
	<div id="footer">
		<table width="600" border="0" cellspacing="0" cellpadding="0">
			<tr valign="top">
				<td width="475">
				<p id="explain"><img src="themes/walktheme/images/splash_logo_tnt.png" alt="TNT" border="0" class="left"  /><span class="orange">*</span> For every person who clicks above, TNT Global Express, Logistics & Mail will sponsor the cost of feeding one child for one day through the U.N. World Food Programme's Global School Feeding campaign.</p>
				</td>
				<td width="125"><a href="virtual/F1"><img src="themes/walktheme/images/splash_btn_f1.png" alt="Race Against Hunger" width="125" height="115" border="0" align="right" /></a></td>
			</tr>
			<tr valign="top">
				<td colspan="2" align="center">
					<p id="skip"><a href="home">skip to walk the world &raquo;</a></p>
					<p id="legal">&copy; 2005 World Food Programme | <a href="privacy">Privacy Policy</a></p>
				</td>
			</tr>
		</table>
	</div>

</div><!-- close page -->

</body>

</html>