<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <style>
    <!--
    	html, body {margin:0; padding:0; background-color:#fff;}
    	#container {margin:0 auto; width:670px; font:normal 10pt arial,helvetica,sans-serif;}
      #header {width:670px; margin:0; text-align:center;}
    	#body {width:630px; margin:0; padding:5px 20px; text-align:left; background-color:#fff;}
    	#footer {width:670px; height:35px; margin:0; padding:5px 0 0 0; font-size:9pt; text-align:center; color:#fff;}
      .ad_footer, .message, .article  {font-size:10pt; padding:0;}
    	.frm_title, .frm_txt {font-size:12pt;}
      .frm_txt {padding-bottom:15px;}
      .links {font-size:10pt; font-style:italic;}
      .article_title {font-size:12pt;}
      .dyn_content { padding-top:10px;}
    -->
    </style>
    <base href="<?php print url('',NULL,NULL,TRUE) ?>" />
  </head>
  <body>
    <div id="container">
      <div id="header"><?php print l("<img src='$forward_header_image' border='0' alt='$site_name'>", '',NULL,NULL,NULL,TRUE,TRUE) ?></div>
      <div id="body">
        <div class="frm_title"><h3><?php print $forward_message ?>:</h3></div>
        <div class="frm_txt"><p><?php print $message ?></p></div>
        <div class="links"><?php print l(t('Click here to read more on our site'), 'home',NULL,NULL,NULL,TRUE) ?></div>
        <div class="dyn_content"><br /><?php print $dynamic_content ?></div>
        <div class="ad_footer"><br /><?php print $forward_ad_footer ?><br></div>
      </div>
      <div id="footer"><?php print $forward_footer ?></div>
    </div>
  </body>
</html>


