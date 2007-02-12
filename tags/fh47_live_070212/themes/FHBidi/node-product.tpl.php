<div class="node<?php print ($sticky) ? " sticky" : ""; ?>">
  <?php if ($page == 0): ?>
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php endif; ?>
  <?php print $picture ?>
  <div class="content">
  <?php print $content ?>
  </div>
<?php if ($links): ?>
    <?php if ($picture): ?>
      <br class='clear' />
    <?php endif; ?>
  <div class="links"><?php // print $links ?></div>
<?php endif; ?>
<?php if ( ($store) && ($page <> 0) ): ?>
  <div class="terms">( store: <?php print $store_link ?> )</div>
<?php endif; ?>

</div>
