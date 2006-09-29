<?php /* Smarty version 2.6.12-dev, created on 2006-06-09 08:28:44
         compiled from CRM/Contact/Form/Search/EmptyResults.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Contact/Form/Search/EmptyResults.tpl', 4, false),array('function', 'crmURL', 'CRM/Contact/Form/Search/EmptyResults.tpl', 7, false),)), $this); ?>
<div class="messages status">
  <dl>
    <dt><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/Inform.gif" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>status<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>" /></dt>
    <dd>
        <?php if ($this->_tpl_vars['context'] == 'smog'): ?>
            <?php ob_start();  echo CRM_Utils_System::crmURL(array('q' => "context=amtg&amtgID=".($this->_tpl_vars['group']['id'])."&reset=1"), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean();  $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['group']['title'],'2' => $this->_tpl_vars['crmURL'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>%1 has no members which match your search criteria. You can <a href="%2">add members here.</a><?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        <?php else: ?>
            <?php if ($this->_tpl_vars['qill']):  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>No matches found for:<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
            <ul>
            <?php $_from = $this->_tpl_vars['qill']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['criteria']):
?>
                <li><?php echo $this->_tpl_vars['criteria']; ?>
</li>
            <?php endforeach; endif; unset($_from); ?>
            </ul>
            <br />
            <?php else: ?>
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>No matches found.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
            <?php endif; ?>
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Suggestions:<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
            <ul>
            <li><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>check your spelling<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></li>
            <li><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>try a different spelling or use fewer letters<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></li>
            <li><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>if you are searching within a Group or for Tagged contacts, try 'any group' or 'any tag'<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></li>
            <?php if ($this->_tpl_vars['context'] != 'Profile'): ?>
            <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/addI','q' => 'c_type=Individual&reset=1'), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURLI', ob_get_contents());ob_end_clean(); ?>
            <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/addO','q' => 'c_type=Organization&reset=1'), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURLO', ob_get_contents());ob_end_clean(); ?>
            <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/addH','q' => 'c_type=Household&reset=1'), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURLH', ob_get_contents());ob_end_clean(); ?>
            <li><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['crmURLI'],'2' => $this->_tpl_vars['crmURLO'],'3' => $this->_tpl_vars['crmURLH'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>add a <a href="%1">New Individual</a>, <a href="%2">Organization</a> or <a href="%3">Household</a><?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></li>
            <li><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>make sure you have enough privileges in the access control system<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></li>
            <?php endif; ?>
            </ul>
        <?php endif; ?>
    </dd>
  </dl>
</div>