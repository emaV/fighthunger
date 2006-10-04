<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><? print strip_tags("$J1 $J2") ?></title>
	<style type="text/css" media="all">@import "<? print $base_url?>/themes/walktheme/f1_style.css";</style>
	<?php print $head ?>
</head>
<body>

<div align="center">

<table border="0" width="715" cellspacing="0" cellpadding="0">
	<tr valign="top">
		<td colspan="3" align="right">
		<nobr>
		<a href="http://www.clubdejeuners.org/" target="_blank"><img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_logo_club.gif" alt="Le Club" width="115" height="47" border="0" /></a>
		<a href="http://www.grandprix.ca/" target="_blank"><img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_logo_gpc.gif" alt="Grand Prix" width="115" height="47" border="0" /></a>
		<a href="http://www.wfp.org/" target="_blank"><img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_logo_wfp.gif" alt="World Food Programme" width="115" height="47" border="0" /></a>
		</nobr>
		</td>
	</tr>
	<tr valign="top">
		<td width="250"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="250" height="1" border="0" /></td>
		<td width="15"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="15" height="1" border="0" /></td>
		<td width="450"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="450" height="1" border="0" /></td>
	</tr>
	<tr valign="top">
		<td colspan="2"><img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_hed_drive_l.gif" alt="Drive away hunger" width="265" height="108" border="0" />
<br />
<div class="movie">

<? if ($vid == "mov"): ?>
<object codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="196" width="240" classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B">
<param value="<? print $base_url ?>/misc/video/<? print $la ?>_trulli_pre.mov" name="src" />
<param value="false" name="controller" />
<param value="myself" name="target" />
<param value="<? print $base_url ?>/misc/video/trulli_50.mov" name="href" />
<param value="http://www.apple.com/quicktime/download/" name="pluginspage" />
<embed pluginspage="http://www.apple.com/quicktime/download/indext.html" border="0" bgcolor="FFFFFF" src="<? print $base_url ?>/misc/video/<? print $la ?>_trulli_pre.mov" href="<? print $base_url ?>/misc/video/trulli_50.mov" target="myself" controller="false" height="196" width="240" />
</object>

<? else: ?>

<OBJECT ID="MediaPlayer1" width="240" height="226" 
   classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95"
   CODEBASE="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715"
        standby="Loading Microsoft¨ Windows¨ Media Player components..." 
        type="application/x-oleobject">
  <PARAM NAME="AutoStart" VALUE="True">
  <PARAM NAME="FileName" VALUE="<? print $base_url ?>/misc/video/trulli_50.wmv">
  <PARAM NAME="ShowControls" VALUE="True">
  <PARAM NAME="width" VALUE="240">
  <PARAM NAME="height" VALUE="226">
  <PARAM NAME="ShowStatusBar" VALUE="False">
  <EMBED type="application/x-mplayer2" 
   pluginspage="http://www.microsoft.com/Windows/MediaPlayer/"
   name="MediaPlayer1"
   filename="<? print $base_url ?>/misc/video/trulli_50.wmv"
   width="240"
   height="226"
   autostart="1"
   showcontrols="1">
  </EMBED>
</OBJECT> 

<? endif; ?>

</div>

<center>
<table><tr><td>
<a href="<? print $base_url ?>/F1/vid/mov">
<img src="<? print $base_url ?>/themes/walktheme/f1_images/quicktime.png" />
</a>
</td><td>
<a href="<? print $base_url ?>/F1/vid/wmv">
<img src="<? print $base_url ?>/themes/walktheme/f1_images/wmv.png" />
</a>
</td></tr></table>
</center>

<p align="center">
<? print $F ?>
</p>
		</td>
		<td>
			<table border="0" width="450" cellspacing="0" cellpadding="0" id="<? print $la ?>_how">
				<tr valign="top">
					<td width="450" id="<? print $la ?>_how_top">
					<img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_hed_drive_r.gif" alt="Drive away hunger" width="140" height="108" border="0" align="left" />
                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="286" height="188" id="grandprix_counter1" align="middle">
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="movie" value="misc/counter/<? print $la ?>_counter.swf?counter=<? print $count ?>" />
                <param name="quality" value="high" />
                <param name="wmode" value="transparent" />
                <param name="bgcolor" value="#ffffff" />
                <embed src="misc/counter/<? print $la ?>_counter.swf?counter=<? print $count ?>" quality="high" wmode="transparent" bgcolor="#ffffff" width="286" height="188" name="grandprix_counter1" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                </object>
<br/><br/>
<p>
<? print $B ?>
</p>

<? print $frontform ?>

					</td>
				</tr>
				<tr valign="top">
					<td width="450" id="how_bottom"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="450" height="30" border="0" /></td>
				</tr>
				<tr>
					<td align="right" id="support_lang">
                        <?php print $l_link ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><p id="copyright">
		<a target="wfp" href="http://www.wfp.org/"><? print $H ?></a>
		&nbsp;|&nbsp;
		<a target="fh" href="http://www.fighthunger.org/">FightHunger.org</a>
		&nbsp;|&nbsp;
		<a target="privacy" href="http://www.fighthunger.org/privacy"><? print $I ?></a>		
		</p></td>
	</tr>
</table>

</div><!-- center align -->

</body>
</html>
