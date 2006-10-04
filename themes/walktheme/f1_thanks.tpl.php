<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><? print strip_tags($M) ?></title>
<style type="text/css" media="all">@import "<? print $base_url ?>/themes/walktheme/f1_style.css";</style>
<?php print $head ?>
</head>
<body>

<div align="center">

<table border="0" width="715" cellspacing="0" cellpadding="0" id="thankstoyou">
<tr valign="top">
  <td width="350">
  <? if (!$nothanks): ?>
          <img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_hed_thanks.gif" alt="<? print $M ?>" width="350" height="80" border="0" />
          <p>
            <? print $N ?>
          </p>
      <? endif; ?>
  </td>
  <td width="15"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="15" height="1" border="0" /></td>
  <td width="350" align="right">
  <nobr>
  <a href="http://www.clubdejeuners.org/" target="_blank"><img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_logo_club.gif" alt="Le Club" width="115" height="47" border="0" /></a>
  <a href="http://www.grandprix.ca/" target="_blank"><img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_logo_gpc.gif" alt="Grand Prix" width="115" height="47" border="0" /></a>
  <a href="http://www.wfp.org/" target="_blank"><img src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_logo_wfp.gif" alt="World Food Programme" width="115" height="47" border="0" /></a>
</nobr>
  <br /><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="1" height="25" border="0" />

<table border="0" celpadding="0" cellspacing="0"><tr><td style="text-align: right; padding-right: 10px;">
<!--<? print $U ?>-->
</td><td style="padding-right: 6px;">
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="140" height="120" id="slideshow" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="misc/slideshow/slideshow.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<embed src="misc/slideshow/slideshow.swf" quality="high" bgcolor="#ffffff" width="140" height="120" name="slideshow" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</td></table>

  </td>
</tr>
<tr>
  <td><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="350" height="10" border="0" /></td>
  <td><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="15" height="10" border="0" /></td>
  <td><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="350" height="10" border="0" /></td>
</tr>
<tr valign="top">
  <td>
  <table border="0" width="350" cellspacing="0" cellpadding="0" id="<? print $la ?>_taf">
    <tr valign="top">
      <td width="30"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="30" height="60" border="0" /></td>
      <td width="55"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="55" height="1" border="0" /></td>
      <td width="235"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="235" height="1" border="0" /></td>
      <td width="30"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="30" height="1" border="0" /></td>
    </tr>
    <tr valign="top">
      <td colspan="2"></td>
      <td>
        <? print $leftboxtop ?>
      </td>
    </tr>
    <tr valign="top">
      <td></td>
      <td colspan="2">
        <p>
          <? print $leftboxlow ?>
        </p>
      </td>
      <td></td>
    </tr>
    <tr valign="top">
      <td colspan="4" id="taf_bottom">
      <img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="1" height="30" border="0" />
      </td>
    </tr>
  </table>
  </td>
  <td></td>
  <td>
  <table border="0" width="350" cellspacing="0" cellpadding="0" id="<? print $la ?>_support">
    <tr valign="top">
      <td width="30"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="30" height="60" border="0" /></td>
      <td width="55"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="55" height="1" border="0" /></td>
      <td width="235"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="235" height="1" border="0" /></td>
      <td width="30"><img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="30" height="1" border="0" /></td>
    </tr>
    <tr valign="top">
      <td></td>
      <td></td>
      <td>
      <p>
        <? print $W ?>
      </p>
      <a href="<? print $base_url ?>/F1/contribute">
                  <img align="right" src="<? print $base_url ?>/themes/walktheme/f1_images/<? print $la ?>_btn_contribute.gif" alt="Send" width="144" height="38" border="0" />
      </a>
      <br class="clear"/>
      <br class="clear"/>
      <br class="clear"/>
      <p>
          <? print $W2 ?>
      </p>
      </td>
      <td></td>
    </tr>
    <tr valign="top">
      <td colspan="4" id="support_bottom">
      <img src="<? print $base_url ?>/themes/walktheme/f1_images/shim.gif" width="1" height="30" border="0" />
      </td>
    </tr>
    <tr>
      <td colspan="4" align="right" id="support_lang">
          <? print $l_link ?>
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
