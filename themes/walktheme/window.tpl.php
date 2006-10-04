<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title><?php print $title ? $title.' | '.$site_name : $site_name; ?></title>
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <base href="<? global $base_url; print $base_url; ?>/" />
  <style type="text/css" media="all">@import "misc/drupal.css";</style>  
  <style type="text/css" media="all">@import "themes/walktheme/style.css";</style>
</head>
<body>

<div id="window">

<div id="header">
&nbsp;
</div>

<div id="content">

<?php if (!empty($title)): ?>
<h2><?php print $title ?></h2>
<?php endif; ?>

<?php print $content ?>
</div><!--content-->

<div id="footer">
&nbsp;
</div>

</div><!--window-->

</body>