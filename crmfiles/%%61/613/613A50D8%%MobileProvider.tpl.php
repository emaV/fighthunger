<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 06:16:48
         compiled from CRM/Admin/Page/MobileProvider.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Admin/Page/MobileProvider.tpl', 2, false),array('function', 'cycle', 'CRM/Admin/Page/MobileProvider.tpl', 21, false),array('function', 'crmURL', 'CRM/Admin/Page/MobileProvider.tpl', 32, false),)), $this); ?>
<div id="help">
    <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>When recording mobile phone numbers for contacts, it may be useful to include the Mobile Phone Service Provider (e.g. Cingular, Sprint, etc.). CiviCRM is installed with the most commonly encountered service providers. Administrators may define as many additional providers as needed.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
</div>

<?php if ($this->_tpl_vars['action'] == 1 || $this->_tpl_vars['action'] == 2 || $this->_tpl_vars['action'] == 8): ?>
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Admin/Form/MobileProvider.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['rows']): ?>
<div id="mobprovider">
<p></p>
    <div class="form-item">
        <?php echo '<table><tr class="columnheader"><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Mobile Phone Provider';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Enabled?';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th></th></tr>';  $_from = $this->_tpl_vars['rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
 echo '<tr class="';  echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this); echo ' ';  echo $this->_tpl_vars['row']['class'];  echo '';  if (! $this->_tpl_vars['row']['is_active']):  echo ' disabled';  endif;  echo '"><td> ';  echo $this->_tpl_vars['row']['name'];  echo '</td><td>';  if ($this->_tpl_vars['row']['is_active'] == 1):  echo ' ';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Yes';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo ' ';  else:  echo ' ';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'No';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo ' ';  endif;  echo '</td><td>';  echo $this->_tpl_vars['row']['action'];  echo '</td></tr>';  endforeach; endif; unset($_from);  echo '</table>'; ?>


        <?php if ($this->_tpl_vars['action'] != 1 && $this->_tpl_vars['action'] != 2): ?>
	    <div class="action-link">
    	<a href="<?php echo CRM_Utils_System::crmURL(array('q' => "action=add&reset=1"), $this);?>
" id="newMobileProvider">&raquo; <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>New Mobile Phone Provider<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php else: ?>
    <div class="messages status">
    <dl>
        <dt><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/Inform.gif" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>status<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"/></dt>
        <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/admin/mobileProvider','q' => "action=add&reset=1"), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean(); ?>
        <dd><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['crmURL'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>There are no Mobile Providers entered for this Contact. You can <a href="%1">add one</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        </dl>
    </div>    
<?php endif; ?>