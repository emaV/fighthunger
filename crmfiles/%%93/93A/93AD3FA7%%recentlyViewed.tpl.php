<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 07:35:51
         compiled from CRM/common/recentlyViewed.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/common/recentlyViewed.tpl', 4, false),)), $this); ?>
<div id="recently-viewed">
    <ul>
    <li><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Recently Viewed:<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></li>
    <?php $_from = $this->_tpl_vars['recentlyViewed']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
         <li><a href="<?php echo $this->_tpl_vars['item']['url']; ?>
"><?php echo $this->_tpl_vars['item']['icon']; ?>
</a><a href="<?php echo $this->_tpl_vars['item']['url']; ?>
"><?php echo $this->_tpl_vars['item']['title']; ?>
</a></li>
    <?php endforeach; endif; unset($_from); ?>
   </ul>
</div>