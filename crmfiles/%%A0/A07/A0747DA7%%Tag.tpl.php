<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 07:56:26
         compiled from CRM/Tag/Form/Tag.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Tag/Form/Tag.tpl', 3, false),array('function', 'crmURL', 'CRM/Tag/Form/Tag.tpl', 7, false),)), $this); ?>

<fieldset><legend><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Tags<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></legend>
    <p>
    <?php if ($this->_tpl_vars['action'] == 16): ?>
        <?php if ($this->_tpl_vars['permission'] == 'edit'): ?>
            <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/tag','q' => 'action=update'), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean(); ?>
            <?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['displayName'],'2' => $this->_tpl_vars['crmURL'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Current tags for <strong>%1</strong> are highlighted. You can add or remove tags from <a href="%2">Edit Tags</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        <?php else: ?>
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Current tags are highlighted.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        <?php endif; ?>
    <?php else: ?>
        <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Mark or unmark the checkboxes, and click 'Update Tags' to modify tags.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
    <?php endif; ?>
    </p>
    
      <?php $_from = $this->_tpl_vars['tag']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['row']):
?>

        <div class="form-item" id ="rowid<?php echo $this->_tpl_vars['id']; ?>
">

         <?php echo $this->_tpl_vars['form']['tagList'][$this->_tpl_vars['id']]['html']; ?>
 &nbsp;<?php echo $this->_tpl_vars['row']; ?>


        </div>

      <?php endforeach; endif; unset($_from); ?>

        <?php if ($this->_tpl_vars['permission'] == 'edit' && $this->_tpl_vars['action'] == 16): ?>
        </fieldset>
        <div class="action-link">
          <a href="<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/tag','q' => 'action=update'), $this);?>
">&raquo; <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Edit Tags<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></a>
        </div>
    <?php else: ?>
       <div class="form-item"><?php echo $this->_tpl_vars['form']['buttons']['html']; ?>
</div>
       </fieldset>
    <?php endif; ?>

 <script type="text/javascript">
     var fname = "<?php echo $this->_tpl_vars['form']['formName']; ?>
";	
    on_load_init_check(fname);
 </script>