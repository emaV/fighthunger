<!-- fhwalk start -->
<div class="node<?php print ($sticky) ? " sticky" : ""; ?>">

  <?php if ($page == 0): ?>
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php endif; ?>

  <div class="content">
<?php
// When
  print "<h3>" . t("When") . "</h3>";
  print $node->hook_view['event'];
// Where
  print $node->hook_view['location'];
// Route
  print "<h3>" . t("Directions") . "</h3>";
  print $node->location['additional'];
  print "<div style='clear:both;'>&nbsp;</div>";

// Sponsor Local
  if($node->partners) {
    print "<h3>" . t("Local Parners") . "</h3>";
    print $node->hook_view['fhpartner'];
    print "<div style='clear:both;'>&nbsp;</div>";
  }

// Details 
  print $node->tabs['details'];
  print "<div style='clear:both;'>&nbsp;</div>";

// Donation
//  print "<h3>" . t("donation") . "</h3>";
  print $node->hook_view['donation'];
  print "<div style='clear:both;'>&nbsp;</div>";

// Actions
  print $node->tabs['actions'];
  print "<div style='clear:both;'>&nbsp;</div>";

// Country Partners
  if($node->partners_country) {
    print "<h3>" . t("Country Partners") . "</h3>";
    print $node->CP;
    print "<div style='clear:both;'>&nbsp;</div>";
  }

// LCP
  print $node->tabs['LCP'];
  print "<div style='clear:both;'>&nbsp;</div>";
 
// Footer tabs
  print $node->tabs['footer'];
  print "<div style='clear:both;'>&nbsp;</div>";
 
  
// DEBUG
/*
  print "\n\n<!-- DEBUG\n\n";
    
  $hook_array = $node->hook_view;
  foreach($node->hook_view as $key => $value) {
   print "#### \n\n";
   print "#### $key\n\n";
   print "############################################\n";
   print htmlentities($value) . "\n\n";
   print "############################################\n";
  }
 
  print "\n\n\n\n" . htmlentities(print_r($node, true));

  print "\n\nEND DEBUG -->\n\n";
*/

?>
  </div>
<?php if ($links): ?>
  <div class="links"><?php // print $links ?></div>
  <div class="terms"></div>
<?php endif; ?>


</div>



<?php
/*
$alias = db_result(db_query("SELECT dst FROM {url_alias} WHERE src='node/%d'",$node['nid']));
list($campaign, $event) = split('/',  $alias );
$mail_to = "$event.$campaign@fighthunger.org";
$subject = 'subject=' . utf8_encode("tags: $campaign $event");
//$body = 
// print ("<a href='mailto:$mail_to?$subject'>$mail_to</a>");
// print("<br />alias: $alias");
?>


<!-- fhwalk end -->
