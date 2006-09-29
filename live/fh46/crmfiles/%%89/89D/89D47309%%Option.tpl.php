<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 06:03:48
         compiled from CRM/Custom/Form/Option.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Custom/Form/Option.tpl', 2, false),)), $this); ?>
<div class="form-item">
<fieldset><legend><?php if ($this->_tpl_vars['action'] == 8):  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Selection Options<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  else:  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Selection Options<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  endif; ?></legend>
      <?php if ($this->_tpl_vars['action'] == 8): ?>
      <div class="messages status">
        <dl>
          <dt><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/Inform.gif" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>status<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"></dt>
          <dd>    
          <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>WARNING: Deleting this custom option will result in the loss of all data.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?> <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>This action cannot be undone.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?> <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Do you want to continue?<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
          </dd>
       </dl>
      </div>
     <?php else: ?>
	<dl>
        <dt><?php echo $this->_tpl_vars['form']['label']['label']; ?>
</dt><dd>&nbsp;<?php echo $this->_tpl_vars['form']['label']['html']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['form']['value']['label']; ?>
</dt><dd>&nbsp;<?php echo $this->_tpl_vars['form']['value']['html']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['form']['weight']['label']; ?>
</dt><dd>&nbsp;<?php echo $this->_tpl_vars['form']['weight']['html']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['form']['is_active']['label']; ?>
</dt><dd>&nbsp;<?php echo $this->_tpl_vars['form']['is_active']['html']; ?>
</dd>
	    <dt><?php echo $this->_tpl_vars['form']['default_value']['label']; ?>
</dt><dd>&nbsp;<?php echo $this->_tpl_vars['form']['default_value']['html']; ?>
</dd>
        <dt>&nbsp;</dt><dd class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Make this option value 'selected' by default?<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></span></dd>
	</dl>
      <?php endif; ?>
    
    
    <div id="crm-submit-buttons" class="form-item">
    <dl>
    <?php if ($this->_tpl_vars['action'] != 4): ?>
        <dt>&nbsp;</dt><dd><?php echo $this->_tpl_vars['form']['buttons']['html']; ?>
</dd>
    <?php else: ?>
        <dt>&nbsp;</dt><dd><?php echo $this->_tpl_vars['form']['done']['html']; ?>
</dd>
    <?php endif; ?>     </dl>
    </div>

</fieldset>
</div>