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

<!-- header_blocks start -->
<?php if ($header): ?>
  <div id="header_blocks"> 
<?php print $header; ?>
  </div> 
<?php endif; ?>
<!-- header_blocks end -->

  <!-- <a href="."><h1>FightHunger.org: Working to END CHILD HUNGER by 2015</h1></a> -->

  <?php if ($search_box): ?>
	<form action="<?php print $search_url ?>" method="post">
		<div id="search">
			<input class="form-text" type="text" size="15" value="" name="edit[keys]" />
			<input class="form-submit" type="submit" value="<?php print $search_button_text ?>" />
		</div>
	</form>
  <?php endif; ?>

<table border="0" width="100%" class="tbcenter">
  <tr>
    <td width="20%" nowrap="nowrap">
<a href="http://www.wfp.org/" title="World Food Programme page"><img src="<?php print($logo) ?>" alt="World Food Programme logo" title="World Food Programme page" /></a>
    </td>
    <td width="60%" align="center">
<?php
  if ($node->type=='fhgap') {
    $banner = path_to_theme() . '/images/logo_gap_header.jpg';
  } else {
    $banner = theme_get_setting('banner_path');
  }
?>
    
<img src="<?php print base_path() . $banner ?>" alt="banner" title="banner"/>
    </td>
    <td width="20%" nowrap="nowrap">
<span id="site-mission"><a href="<?php print url($language) ?>" title="Index Page"><?php print(drupal_eval(variable_get('site_mission', ''))) ?></a></span>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      <span id="site-slogan"><?php print($site_slogan) ?></span>
    </td>
    <td>&nbsp;</td>
  </tr>
</table>

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
    <?php
    // Add log in / log out links
    global $user;
    if ($user->uid) {
      $primary_links[] = l(t('my area'), 'user');
      $primary_links[] = l(t('log out'), 'logout');
    } else {
      $primary_links[] = l(t('log in'), 'login');
    }
    ?>
    <ul id="primary">
    <?php foreach ($primary_links as $link): ?>
      <li><?php print $link?></li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<!-- top-nav end -->

<!-- content page start -->
<?php
  print "\t<!-- \$_GET['q']: " . $_GET['q'] . " -->\n";
  print "\t<!-- \$node->type: " . $node->type . " -->\n";
  print "\t<!-- arg(0): " . arg(0) . " -->\n\n";
// gorup check  
//  $group_nid = key(og_get_node_groups($node));
//  $group_term = key(taxonomy_node_get_terms_by_vocabulary($group_nid, 6));
  
//  print "\t<!-- \$node->groups: " . print_r(og_get_node_groups($node), true) . " -->\n";
//  print "\t<!-- \group_nid: $group_nid -->\n";
//  print "\t<!-- \group_term: $group_term -->\n";
  
  $template_content = "page-default.tpl.php";
//  if (($group_term) == 25) {
//    $template_content = "page-gap.tpl.php";
//  }
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

