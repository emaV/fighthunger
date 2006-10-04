<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 06:03:44
         compiled from CRM/Custom/Page/Option.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Custom/Page/Option.tpl', 12, false),array('function', 'cycle', 'CRM/Custom/Page/Option.tpl', 20, false),array('function', 'crmURL', 'CRM/Custom/Page/Option.tpl', 33, false),)), $this); ?>
<?php if ($this->_tpl_vars['action'] == 1 || $this->_tpl_vars['action'] == 2 || $this->_tpl_vars['action'] == 4 || $this->_tpl_vars['action'] == 8): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Custom/Form/Option.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['customOption']): ?>
    <div id="field_page">
     <p></p>
        <div class="form-item">
        <?php echo '<table><tr class="columnheader"><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Option Label';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Option Value';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Default';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Weight';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Status?';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th>&nbsp;</th></tr>';  $_from = $this->_tpl_vars['customOption']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
 echo '<tr class="';  echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this); echo ' ';  if (! $this->_tpl_vars['row']['is_active']):  echo ' disabled';  endif;  echo '"><td>';  echo $this->_tpl_vars['row']['label'];  echo '</td><td>';  echo $this->_tpl_vars['row']['value'];  echo '</td><td>';  echo $this->_tpl_vars['row']['default_value'];  echo '</td><td>';  echo $this->_tpl_vars['row']['weight'];  echo '</td><td>';  if ($this->_tpl_vars['row']['is_active'] == 1):  echo ' ';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Active';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo ' ';  else:  echo ' ';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Inactive';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo ' ';  endif;  echo '</td><td>';  echo $this->_tpl_vars['row']['action'];  echo '</td></tr>';  endforeach; endif; unset($_from);  echo '</table>'; ?>

        
        <div class="action-link">
            <a href="<?php echo CRM_Utils_System::crmURL(array('q' => "reset=1&action=add&fid=".($this->_tpl_vars['fid'])), $this);?>
">&raquo; <?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['fieldTitle'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>New Option for "%1"<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></a>
        </div>

        </div>
     </div>

<?php else: ?>
    <?php if ($this->_tpl_vars['action'] == 16): ?>
        <div class="messages status">
        <dl>
        <dt><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/Inform.gif" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>status<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"></dt>
        <dd><?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/admin/custom/group/field/option','q' => "action=add&fid=".($this->_tpl_vars['fid'])), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean();  $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['fieldTitle'],'2' => $this->_tpl_vars['crmURL'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>There are no multiple choice options for the custom field "%1", <a href="%2">add one</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        </dl>
        </div>
    <?php endif;  endif; ?>