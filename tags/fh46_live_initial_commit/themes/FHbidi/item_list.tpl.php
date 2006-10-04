<div class="item-list">
<?php if (isset($title)): ?>
  <h3><?php print $$title ?></h3>
<?php endif; ?>
<?php if (isset($items)): ?>
<ul>
<?php 
foreach ($items as $item) {
  print "  <li>$item</li>\n";
}
?>
</ul>
<?php endif; ?>
</div>
