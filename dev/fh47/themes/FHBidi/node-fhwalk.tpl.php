<!-- fhwalk start -->
<div class="node<?php print ($sticky) ? " sticky" : ""; ?>">

  <?php if ($page == 0): //teaser view ?>
  
    <?php if ($node->in_preview): //preview  ?>
      <h2><?php print $title ?></h2>
      
    <?php else: //normal ?>
      <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>

    <?php endif; ?>

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
  if ($node->in_preview) {
    print '<h3>' . t('Signup settings') . '</h3>';
    print '<ul>'; 
    print '<li>' . t('Signup Approval') . ': <strong>' . _fhsignup_status($node->signup_status) . '</strong></li>'; 
    print '<li>' . t('Show signups') . ': <strong>'        . (($node->signup_show) ? t('yes') : t('no')) . '</strong></li>'; 
    print '<li>' . t('Show signups number') . ': <strong>' . (($node->signup_show_number) ? t('yes') : t('no')) . '</strong></li>'; 
    print '<li>' . t('Show user comments') . ': <strong>'  . (($node->signup_show_comments) ? t('yes') : t('no')) . '</strong></li>'; 
    print '<li>' . t('Group Signup') . ': <strong>'        . (($node->signup_allow_guest) ? t('yes') : t('no')) . '</strong></li>';
    print '</ul>';
  }

?>

  </div>

  <?php else: //full node view ?>

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
?>
  </div>
  
  <?php endif; ?>

</div>
<!-- fhwalk end -->
