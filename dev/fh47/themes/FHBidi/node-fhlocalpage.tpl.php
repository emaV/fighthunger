<div class="node<?php print ($sticky) ? " sticky" : ""; ?>">
  <?php if ($page == 0): ?>
    <h2><?php print $title ?></h2>
  <?php endif; ?>
  <?php print $picture ?>
  <div class="content">
  <?php print $content ?>
  </div>

<?php if ($links): ?>
  <div class="links"><?php print $links ?></div>
<?php endif; ?>

</div>
