<?php /* Smarty version 2.6.12-dev, created on 2006-07-10 08:02:18
         compiled from CRM/Mailing/Page/Browse.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'CRM/Mailing/Page/Browse.tpl', 14, false),array('function', 'cycle', 'CRM/Mailing/Page/Browse.tpl', 16, false),array('function', 'crmURL', 'CRM/Mailing/Page/Browse.tpl', 30, false),array('block', 'ts', 'CRM/Mailing/Page/Browse.tpl', 29, false),)), $this); ?>
<?php if ($this->_tpl_vars['rows']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/pager.tpl", 'smarty_include_vars' => array('location' => 'top')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '<table><tr class="columnheader">';  $_from = $this->_tpl_vars['columnHeaders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header']):
 echo '<th>';  echo $this->_tpl_vars['header']['name'];  echo '</th>';  endforeach; endif; unset($_from);  echo '</tr>';  echo smarty_function_counter(array('start' => 0,'skip' => 1,'print' => false), $this); echo '';  $_from = $this->_tpl_vars['rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
 echo '<tr class="';  echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this); echo '">';  $_from = $this->_tpl_vars['row']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
 echo '<td>';  echo $this->_tpl_vars['value'];  echo '</td>';  endforeach; endif; unset($_from);  echo '</tr>';  endforeach; endif; unset($_from);  echo '</table>'; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/pager.tpl", 'smarty_include_vars' => array('location' => 'bottom')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
<div class="messages status">
    <dl>
        <dt><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/Inform.gif" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>status<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"></dt>
        <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/mailing/send','q' => 'reset=1'), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean(); ?>
        <dd><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['crmURL'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>There are no sent mails. You can <a href="%1">send one</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
    </dl>
   </div>
<?php endif; ?>