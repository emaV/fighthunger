<div class="node node_<?php print $node->type ?>">
  <?php if ($page == 0): ?>
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php endif; ?>
  
  <div class="info">
  <?php 
  if (theme_get_setting('toggle_node_info_' . $node->type)) {
    print t('Posted by %a on %b.', array('%a' => format_name($node), '%b' => format_date($node->created)));    
  }  
  ?>
<?php if (($terms) && ($node->type == "blog")): ?> in
    <span class="terms"> <?php print $terms ?></span>.<?php endif; ?></div>
  <div class="content">
    <?php print $content ?>
  </div>
<?php if ($links): ?>
    <div class="links"><?php print $links ?></div>
<?php endif; ?>
</div>
