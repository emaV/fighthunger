<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title><?php print $head_title ?></title>
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
  
  <?php print $head ?>
  <?php print $styles ?>
</head>

<body>

<!-- page start -->
<div id="page">

<!-- header start -->
<div id="header"> 
  <!-- <a href="."><h1>FightHunger.org: Working to END CHILD HUNGER by 2015</h1></a> -->

  <?php if ($search_box): ?>
	<form action="<?php print $search_url ?>" method="post">
		<div id="search">
			<input class="form-text" type="text" size="15" value="" name="edit[keys]" />
			<input class="form-submit" type="submit" value="<?php print $search_button_text ?>" />
		</div>
	</form>
  <?php endif; ?>

<table border="0">
  <tr><td>
<?php if ($logo) : ?>
    <a href="<?php print url() ?>" title="Index Page"><img src="<?php print($logo) ?>" alt="Fight Hunger Walk the World" /></a>
<?php endif; ?>
    </td>
    <td><img src="<?php print base_path().path_to_theme() ?>/images/fhbannermonuments.gif" /></td>
    <td width='100'>&nbsp;</td>
    <td align="center">
      <span id="site-mission">
<?php
/**
* This php snippet displays (x) days left to a specific event
* Change the values for keyMonth, keyDay and keyYear to suit
* Tested and works with drupal 4.6 and 4.5
*/
/*
$keyMonth = 5;
$keyDay = 21;
$keyYear = 2006; 
$month = date(F);
$mon = date(n);
$day = date(j);
$year = date(Y);
$hours_left = (mktime(0,0,0,$keyMonth,$keyDay,$keyYear) - time())/3600;
$daysLeft = ceil($hours_left/24);
$z = (string)$daysLeft;
if ($daysLeft > 0) {
  print "<b>Counting down to<br />21 May 2006<br />";
  print "<font color=#ff6600>";
  print $z;
  print "</font><br /> days left!</b>";
} else {
  print "<font color=#ff6600>";
  print "<b>21 May 2006<br />The World<br />is<br />Walking!</b>";
  print "</font>";
}
print "<br />";
*/
?>
<font color=#ff6600><b>
Save the date<br />
13 May 2007<br />
Walk with us!<br />
</b></font>
      </span> 
    </td>
  </tr>
  <tr><td>&nbsp;</td><td colspan=3>
<?php if ($site_slogan) : ?>
    <span id="site-slogan">
     <?php print($site_slogan) ?></span>
<?php endif;?>
  </td></tr>
</table>

<?php if ($site_name) : ?>
<h1 id="site-name"><a href="<?php print url() ?>" title="Index Page"><?php print($site_name) ?></a></h1>
<?php endif;?>
<br class="clear" />

</div>
<!-- header end -->

<!-- top-nav start -->
<div id="top-nav">
  <?php if (count($secondary_links)) : ?>
    <ul id="secondary">
    <?php foreach ($secondary_links as $link): ?>
      <li><?php print $link?></li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
	<?php if (count($primary_links)) : ?>
    <ul id="primary">
    <?php foreach ($primary_links as $link): ?>
      <li><?php print $link?></li>
    <?php endforeach; ?>
    <?php global $user; ?>
    <?php if ($user->uid > 0) : ?>
      <li><a href='user'><b>My Area</b></a></li>
      <li><a href='logout'>log out</a></li>
    <?php else: ?>
      <li><a href='login'>log in</a></li>
    <?php endif; ?>
    </ul>
  <?php endif; ?>
</div>
<!-- top-nav end -->

<!-- content page start -->
<?php
  print "\t<!-- \$_GET['q']: " . $_GET['q'] . " -->\n";
  print "\t<!-- \$node->type: " . $node->type . " -->\n";
  print "\t<!-- arg(0): " . arg(0) . " -->\n\n";
  $template_content = "page-default.tpl.php";
  if (($node->type) == 'gathering-2') {
    $template_content = "page-gathering.tpl.php";
  }
  if ( arg(0)=='user') {
    $template_content = "page-user.tpl.php";
  }
  print "\t<!-- template: " . $template_content . " -->\n\n";
  
  include $template_content;
?>
<!-- content page end -->

<!-- <?php print $breadcrumb ?> -->

<!-- footer start -->
<div id="footer">
<?php if ($footer_message) : ?>
  <p><?php print $footer_message;?></p>
<?php endif; ?>
<!-- Validate <a href="http://validator.w3.org/check/referer">XHTML</a> or <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>. -->
</div>
<!-- footer end -->
</div>
<!-- page start -->
<?php print $closure;?>
  </body>
</html>

