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
?>
  </div>

</div>

<!-- fhwalk end -->
