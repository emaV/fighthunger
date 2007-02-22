<div class="node">
  <?php if ($page == 0): ?>
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php endif; ?>
  <div class="content">
  <?php print $content ?>
  </div>
<?php if ($links): ?>
  <div class="links"><?php print $links ?></div>
<?php endif; ?>

</div>
