<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 07:56:22
         compiled from CRM/Contact/Page/View/Note.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Contact/Page/View/Note.tpl', 5, false),array('modifier', 'crmDate', 'CRM/Contact/Page/View/Note.tpl', 7, false),array('modifier', 'mb_truncate', 'CRM/Contact/Page/View/Note.tpl', 48, false),array('modifier', 'count_characters', 'CRM/Contact/Page/View/Note.tpl', 50, false),array('function', 'crmURL', 'CRM/Contact/Page/View/Note.tpl', 9, false),array('function', 'cycle', 'CRM/Contact/Page/View/Note.tpl', 46, false),)), $this); ?>
<?php if ($this->_tpl_vars['action'] == 4): ?>    <?php if ($this->_tpl_vars['notes']): ?>
        <p></p>
        <fieldset>
          <legend><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>View Note<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></legend>
          <div class="form-item">
            <label><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Date:<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></label> <?php echo ((is_array($_tmp=$this->_tpl_vars['note']['modified_date'])) ? $this->_run_mod_handler('crmDate', true, $_tmp) : smarty_modifier_crmDate($_tmp)); ?>

            <p><?php echo $this->_tpl_vars['note']['note']; ?>
</p>
            <input type="button" name='cancel' value="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Done<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>" onclick="location.href='<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/note','q' => 'action=browse'), $this);?>
';"/>        
          </div>
        </fieldset>
        <?php endif;  elseif ($this->_tpl_vars['action'] == 1 || $this->_tpl_vars['action'] == 2): ?>     <p></p>
    <fieldset><legend><?php if ($this->_tpl_vars['action'] == 1):  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>New Note<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  else:  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Edit Note<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  endif; ?></legend>
    <div class="form-item">
        <?php echo $this->_tpl_vars['form']['note']['html']; ?>

        <br/>
        <?php echo $this->_tpl_vars['form']['buttons']['html']; ?>

    </div>
    </fieldset>
<?php endif;  if (( $this->_tpl_vars['action'] == 8 )): ?>
<fieldset><legend><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Delete Note<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></legend>
<div class=status><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['notes'][$this->_tpl_vars['id']]['note'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Are you sure you want to delete the Note "%1"?<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></div>
<dl><dt></dt><dd><?php echo $this->_tpl_vars['form']['buttons']['html']; ?>
</dd></dl>
</fieldset>

<?php endif; ?>


<?php if ($this->_tpl_vars['notes']): ?>
    <div id="notes">
    <div class="form-item">
    <br/>
    <?php echo '<table><tr class="columnheader"><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Note';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Date';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th>';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo 'Created By';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</th><th></th></tr>';  $_from = $this->_tpl_vars['notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['note']):
 echo '<tr class="';  echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this); echo '"><td>';  echo ((is_array($_tmp=$this->_tpl_vars['note']['note'])) ? $this->_run_mod_handler('mb_truncate', true, $_tmp, 80, "...", true) : smarty_modifier_mb_truncate($_tmp, 80, "...", true));  echo '';  echo '';  $this->assign('noteSize', ((is_array($_tmp=$this->_tpl_vars['note']['note'])) ? $this->_run_mod_handler('count_characters', true, $_tmp, true) : smarty_modifier_count_characters($_tmp, true)));  echo '';  if ($this->_tpl_vars['noteSize'] > 80):  echo '<a href="';  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/note','q' => "nid=".($this->_tpl_vars['note']['id'])."&action=view"), $this); echo '">';  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start();  echo '(more)';  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  echo '</a>';  endif;  echo '</td><td>';  echo ((is_array($_tmp=$this->_tpl_vars['note']['modified_date'])) ? $this->_run_mod_handler('crmDate', true, $_tmp) : smarty_modifier_crmDate($_tmp));  echo '</td><td><a href="';  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view','q' => "reset=1&cid=".($this->_tpl_vars['note']['contact_id'])), $this); echo '">';  echo $this->_tpl_vars['note']['createdBy'];  echo '</a></td><td class="nowrap">';  echo $this->_tpl_vars['note']['action'];  echo '</td></tr>';  endforeach; endif; unset($_from);  echo '</table>'; ?>


       <?php if ($this->_tpl_vars['permission'] == 'edit' && ( $this->_tpl_vars['action'] == 16 || $this->_tpl_vars['action'] == 4 || $this->_tpl_vars['action'] == 8 )): ?>
       <div class="action-link">
    	 <a href="<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/note','q' => "cid=".($this->_tpl_vars['contactId'])."&action=add"), $this);?>
">&raquo; <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>New Note<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></a>
       </div>
       <?php endif; ?>
    </div>
 </div>

<?php elseif (! ( $this->_tpl_vars['action'] == 1 )): ?>
   <div class="messages status">
    <dl>
        <dt><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/Inform.gif" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>status<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"></dt>
        <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/note','q' => 'action=add'), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('crmURL', ob_get_contents());ob_end_clean(); ?>
        <dd><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['crmURL'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>There are no Notes for this contact. You can <a href="%1">add one</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
    </dl>
   </div>
<?php endif; ?>