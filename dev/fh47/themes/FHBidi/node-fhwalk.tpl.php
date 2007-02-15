<!-- fhwalk start -->
<div class="node<?php print ($sticky && !$node->in_preview) ? " sticky" : ""; ?>">

<?php if ($node->in_preview): //preview  ?>
<!-- fhwalk preview start -->

  <div class="sticky">
  <h2><?php print $title ?></h2>
  
    <div class="content">

<?php
// When
  print "<h3>" . t("When") . "</h3>";
  print $node->hook_view['event'];
// Where
  print $node->hook_view['location'];

// Other Details
  if($node->location['additional']<>'') {
    print "<h3>" . t("Directions") . "</h3>";
    print $node->location['additional'];
  }
  if($node->route<>'') {
    print '<h3>' . t('Route') . '</h3>'; 
    print $node->route;
  }
  if($node->body_plain) {
    print '<h3>' . t('Walk Information') . '</h3>'; 
    print $node->body_plain;
  }
  
  // Signup
  print '<h3>' . t('Signup settings') . '</h3>';
  print '<ul>'; 
  print '<li>' . t('Signup Approval') . ': <strong>' . _fhsignup_status($node->signup_status) . '</strong></li>'; 
  print '<li>' . t('Show signups') . ': <strong>'        . (($node->signup_show) ? t('yes') : t('no')) . '</strong></li>'; 
  print '<li>' . t('Show signups number') . ': <strong>' . (($node->signup_show_number) ? t('yes') : t('no')) . '</strong></li>'; 
  print '<li>' . t('Show user comments') . ': <strong>'  . (($node->signup_show_comments) ? t('yes') : t('no')) . '</strong></li>'; 
  print '<li>' . t('Group Signup') . ': <strong>'        . (($node->signup_allow_guest) ? t('yes') : t('no')) . '</strong></li>';
  print '</ul>';
?>
    </div>
  </div>
<!-- fhwalk preview end -->
  
<?php else: //not preview  ?>
  
  <?php if ($page == 0): //no page ?>
  <h2 class="title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php endif; ?>
    
  <div class="content">

  <?php if ($teaser == 1): //teaser view ?>

<!-- fhwalk teaser start -->

<?php
  // Walk Information
  if($node->body_plain) {
    print '<h3>' . t('Walk Information') . "</h3>\n"; 
    print "$node->body_plain\n\n";
  }
  
  // When
  print "<h3>" . t("When") . "</h3>\n";
  print $node->hook_view['event'] . "\n\n";
  
  // Where
  print $node->hook_view['location'] . "\n\n";

  // Other Details
  if($node->location['additional']<>'') {
    print "<h3>" . t("Directions") . "</h3>\n";
    print $node->location['additional'] . "\n\n";
  }
  if($node->route<>'') {
    print '<h3>' . t('Route') . "</h3>\n";
    print $node->route . "\n\n";
  }
?>
<!-- fhwalk teaser end -->

  <?php else: //full node view ?>

<!-- fhwalk body start -->
  
<?php

  // Walk Information
  if($node->body_plain) {
    print '<h3>' . t('Walk Information') . "</h3>\n"; 
    print $node->body_plain . "\n\n";
  }

  // When
  print "<h3>" . t("When") . "</h3>\n"; 
  print $node->hook_view['event'] . "\n\n";
  
  // Where
  print $node->hook_view['location'] . "\n\n";
  
  // Route
  if($node->location['additional']) {
    print "<h3>" . t("Directions") . "</h3>\n"; 
    print $node->location['additional'] . "\n";
    print "<div style='clear:both;'>&nbsp;</div>\n\n";
  }

  // Sponsor Local
  if($node->partners) {
    print "<h3>" . t("Local Partners") . "</h3>\n"; 
    print $node->hook_view['fhpartner'] . "\n";
    print "<div style='clear:both;'>&nbsp;</div>\n\n";
  }

  // Details 
  print $node->tabs['details'];
  print "<div style='clear:both;'>&nbsp;</div>\n\n";

  // Donation
//  print "<h3>" . t("donation") . "</h3>";
  print $node->hook_view['donation'] . "\n";
  print "<div style='clear:both;'>&nbsp;</div>\n\n";

  // Actions
  print $node->tabs['actions'] . "\n";
  print "<div style='clear:both;'>&nbsp;</div>\n\n";

  // Country Partners
  if($node->partners_country) {
    print "<h3>" . t("Country Partners") . "</h3>\n"; 
    print $node->CP . "\n";
    print "<div style='clear:both;'>&nbsp;</div>\n\n";
  }

  // LCP
  print $node->tabs['LCP'] . "\n";
  print "<div style='clear:both;'>&nbsp;</div>\n\n";
 
  // Footer tabs
  print $node->tabs['footer'] . "\n";
  print "<div style='clear:both;'>&nbsp;</div>\n\n";
?>

<!-- fhwalk body end -->
  
  <?php endif; ?>
  
  </div>
  
<?php endif; ?>

</div>
<!-- fhwalk end -->
