<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title><?php print $title ? $title.' | '.$site_name : $site_name; ?></title>
  <meta http-equiv="Content-Style-Type" content="text/css" />

<!-- start Google Analytics script -->  
  <script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
  </script>
  <script type="text/javascript">
  _uacct = "UA-135190-3";
  urchinTracker();
  </script>    
<!-- end Google Analytics script -->  
 
  <script type="text/javascript"> </script><!-- FOUC hack -->
<!-- begin head -->
  <?php print $head ?>
<!-- end head / begin styles -->
  <?php print $styles ?>
<!-- end styles -->
  <?php
    if (arg(0) == "home"){
        print '<style type="text/css" media="all">@import "themes/walktheme/style_frontpage.css";</style>';
    } else {
        print '<style type="text/css" media="all">@import "themes/walktheme/style_subpage.css";</style>';
    }
  ?>
</head>
<body <?php print theme("onload_attribute"); ?>>

<div id="page">

<div id="header">
  <a href="."><h1>FightHunger.org: Working to END CHILD HUNGER by 2015</h1></a>

	<div id="helpus">
	<img src="themes/walktheme/images/img_helpus.gif" alt="Help end child hunger" width="152" height="75" border="0" />
<form method="post" action="join">
<input type="hidden" name="edit[source]" value="header"/>
<input type="text" name="edit[mail]" alt="Email Address" class="signup_email" border="0" size="15" value="Email Address" align="absmiddle" ONFOCUS="if(this.value=='Email Address')this.value='';" ONBLUR="if(this.value=='')this.value='Email Address';">
<input class="go" type="image" align="absmiddle" name="submit" value="submit" src="themes/walktheme/images/btn_help_join.gif" border="0" />
</form>
	</div><!-- end helpus -->

</div><!-- end header-->

<div id="navbar">
<ul id="nav">
<?php   global $extralink; 
// this line creates the login/logout link
//        $lastlink = array_pop($primary_links);
        foreach ($primary_links as $link): ?>
      <li><?php print $link?></li>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<?php endforeach; print "<li>$extralink</li>"; ?>
</ul>
</div><!-- end nav -->
 
<div id="content" <?php global $fc_thumb; if ((arg(0) == "flashcard") && (empty($fc_thumb))) print "class='content_wider'"; ?>>
<div id="main" >
<?php //if ($_GET['q'] == 'blog') { include('modules/wtw_map/map.inc.html'); } ?>
<?php if ($title != ""): ?>
          <h2 class="content-title"><?php print $title ?></h2>
<?php endif; ?>
<? if (($tabs != "") && (user_access("view tabs") || (arg(0) == "faq"))): ?>
          <?php print $tabs ?>
<?php endif; ?>
<?php if ($mission != ""): ?>
          <p id="mission"><?php print $mission ?></p>
<?php endif; ?>
<?php if ($help != ""): ?>
          <p id="help"><?php print $help ?></p>
<?php endif; ?>
<?php if ($messages != ""): ?>
          <div id="message"><?php print $messages ?></div>
<?php endif; ?>

        <!-- start main content -->
        <?php print($content) ?>
        <!-- end main content -->

<?php global $hidecol; if (!$hidecol): ?>
</div><!-- close main -->
<? endif; ?>

<?php global $hidecol; global $fc_thumb; if (!(($hidecol) || ((arg(0) == "flashcard") && (empty($fc_thumb))))) : ?>
    <div id="sidebar">
    <?php if (empty($fc_thumb)): ?>
    <?php print(preg_match("/gallery/", $_SERVER['REQUEST_URI']) ? '' : $sidebar_right); ?>
    <?php else: ?>
    <?php print $fc_thumb; ?>
    <?php endif; ?>
    </div> <!-- close sidebar -->
<?php endif; ?>

<br clear="all" />
</div><!-- close content -->

<div id="footer">
		<div id="legal">&copy; 2005 World Food Programme | <a href="privacy">Privacy Policy</a> | <a href="disclaimer">Disclaimer</a> | Empowered by <a href="http://www.echoditto.com/" target="_new">EchoDitto</a><br/>
Need help? <a href="mailto:website@fighthunger.org">Contact us</a>.</div>
		<div id="tnt"><img name="logo_footer" src="themes/walktheme/images/logo_footer.png" width="200" height="65" border="0" id="logo_footer" usemap="#m_logo_footer" alt="" />
<map name="m_logo_footer" id="m_logo_footer">
<area shape="poly" coords="15,23,72,23,72,58,15,58,15,23" href="http://www.tnt.com/" title="TNT" alt="TNT" target="_new" />
<area shape="poly" coords="105,23,138,23,138,58,105,58,105,23" href="http://www.wfp.org/" title="World Food Programme" alt="World Food Programme" target="_new" />
</map></div>
</div>

</div><!-- close page -->
<?php print $closure;?>
</body>
</html>
