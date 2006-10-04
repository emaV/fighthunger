<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 07:56:03
         compiled from CRM/History/Selector/Activity.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'crmURL', 'CRM/History/Selector/Activity.tpl', 5, false),array('function', 'counter', 'CRM/History/Selector/Activity.tpl', 65, false),array('function', 'cycle', 'CRM/History/Selector/Activity.tpl', 68, false),array('block', 'ts', 'CRM/History/Selector/Activity.tpl', 5, false),array('modifier', 'mb_truncate', 'CRM/History/Selector/Activity.tpl', 70, false),array('modifier', 'crmDate', 'CRM/History/Selector/Activity.tpl', 71, false),)), $this); ?>
<?php if ($this->_tpl_vars['history'] != 1): ?>
        <?php if ($this->_tpl_vars['totalCountOpenActivity']): ?>
        <fieldset><legend><a href="<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "show=1&action=browse&history=1&cid=".($this->_tpl_vars['contactId'])), $this);?>
"><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/TreeMinus.gif" class="action-icon" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>close section<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"/></a><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Open Activities<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></legend>
    <?php else: ?>
        <div class="data-group">
        <dl><dt><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Open Activities<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dt>
        <?php if ($this->_tpl_vars['permission'] == 'edit'): ?>
            <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "activity_id=1&action=add&reset=1&cid=".($this->_tpl_vars['contactId'])), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('mtgURL', ob_get_contents());ob_end_clean(); ?>
            <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "activity_id=2&action=add&reset=1&cid=".($this->_tpl_vars['contactId'])), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('callURL', ob_get_contents());ob_end_clean(); ?>
            <dd><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['mtgURL'],'2' => $this->_tpl_vars['callURL'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>No open activities. You can schedule a <a href="%1">meeting</a> or a <a href="%2">call</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        <?php else: ?>
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>There are no open activities for this contact.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        <?php endif; ?>
        </dl>
        </div>
    <?php endif;  else: ?>
        <div id="openActivities[show]" class="data-group">
        <?php if ($this->_tpl_vars['totalCountOpenActivity']): ?>
            <a href="<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "show=1&action=browse&history=0&cid=".($this->_tpl_vars['contactId'])), $this);?>
"><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/TreePlus.gif" class="action-icon" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>open section<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"/></a><label><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Open Activities<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></label> (<?php echo $this->_tpl_vars['totalCountOpenActivity']; ?>
)
        <?php else: ?>
            <dl><dt><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Open Activities<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dt>
            <?php if ($this->_tpl_vars['permission'] == 'edit'): ?>
                <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "activity_id=1&action=add&reset=1&cid=".($this->_tpl_vars['contactId'])), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('mtgURL', ob_get_contents());ob_end_clean(); ?>
                <?php ob_start();  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "activity_id=2&action=add&reset=1&cid=".($this->_tpl_vars['contactId'])), $this); $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('callURL', ob_get_contents());ob_end_clean(); ?>
                <dd><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['mtgURL'],'2' => $this->_tpl_vars['callURL'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>No open activities. You can schedule a <a href="%1">meeting</a> or a <a href="%2">call</a>.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
            <?php else: ?>
                <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>There are no open activities for this contact.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
            <?php endif; ?>
            </dl>
        <?php endif; ?>
    </div>
    <?php if ($this->_tpl_vars['totalCountActivity']): ?>
        <fieldset><legend><a href="<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "show=1&action=browse&history=0&cid=".($this->_tpl_vars['contactId'])), $this);?>
"><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/TreeMinus.gif" class="action-icon" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>close section<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"/></a><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Activity History<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></legend>
    <?php else: ?>
        <div class="data-group">
            <dl><dt><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Activity History<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dt><dd><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>No activity history for this contact.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd></dl>
        </div>
    <?php endif;  endif; ?>

<?php if ($this->_tpl_vars['rows']): ?>
    <form title="activity_pager" action="<?php echo CRM_Utils_System::crmURL(array(), $this);?>
" method="post">

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/pager.tpl", 'smarty_include_vars' => array('location' => 'top')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <?php echo '<table><tr class="columnheader">';  $_from = $this->_tpl_vars['columnHeaders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header']):
 echo '<th>';  if ($this->_tpl_vars['header']['sort']):  echo '';  $this->assign('key', $this->_tpl_vars['header']['sort']);  echo '';  echo $this->_tpl_vars['sort']->_response[$this->_tpl_vars['key']]['link'];  echo '';  else:  echo '';  echo $this->_tpl_vars['header']['name'];  echo '';  endif;  echo '</th>';  endforeach; endif; unset($_from);  echo '</tr>';  echo smarty_function_counter(array('start' => 0,'skip' => 1,'print' => false), $this); echo '';  $_from = $this->_tpl_vars['rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
 echo '';  if ($this->_tpl_vars['history'] == 1):  echo '<tr class="';  echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this); echo '"><td>';  echo $this->_tpl_vars['row']['activity_type'];  echo '</td><td>';  echo ((is_array($_tmp=$this->_tpl_vars['row']['activity_summary'])) ? $this->_run_mod_handler('mb_truncate', true, $_tmp, 33, "...", true) : smarty_modifier_mb_truncate($_tmp, 33, "...", true));  echo '</td><td>';  echo ((is_array($_tmp=$this->_tpl_vars['row']['activity_date'])) ? $this->_run_mod_handler('crmDate', true, $_tmp) : smarty_modifier_crmDate($_tmp));  echo '</td><td>';  echo $this->_tpl_vars['row']['action'];  echo '</td></tr>';  else:  echo '<tr class="';  echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this); echo '"><td>';  echo $this->_tpl_vars['row']['activity_type'];  echo '</td><td><a href="';  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "activity_id=".($this->_tpl_vars['row']['activity_type_id'])."&action=view&id=".($this->_tpl_vars['row']['id'])."&cid=".($this->_tpl_vars['contactId'])."&history=0"), $this); echo '">';  echo ((is_array($_tmp=$this->_tpl_vars['row']['subject'])) ? $this->_run_mod_handler('mb_truncate', true, $_tmp, 33, "...", true) : smarty_modifier_mb_truncate($_tmp, 33, "...", true));  echo '</a></td><td>';  if ($this->_tpl_vars['contactId'] != $this->_tpl_vars['row']['sourceID']):  echo '<a href="';  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view','q' => "reset=1&cid=".($this->_tpl_vars['row']['sourceID'])), $this); echo '">';  echo $this->_tpl_vars['row']['sourceName'];  echo '</a>';  else:  echo '';  echo $this->_tpl_vars['row']['sourceName'];  echo '';  endif;  echo '</td><td>';  if ("$".($this->_tpl_vars['contactId']) != $this->_tpl_vars['row']['targetID'] && $this->_tpl_vars['contactId'] == $this->_tpl_vars['row']['sourceID']):  echo '<a href="';  echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view','q' => "reset=1&cid=".($this->_tpl_vars['row']['targetID'])), $this); echo '">';  echo $this->_tpl_vars['row']['targetName'];  echo '</a>';  else:  echo '';  echo $this->_tpl_vars['row']['targetName'];  echo '';  endif;  echo '</td><td>';  echo ((is_array($_tmp=$this->_tpl_vars['row']['date'])) ? $this->_run_mod_handler('crmDate', true, $_tmp) : smarty_modifier_crmDate($_tmp));  echo '</td><td>';  echo $this->_tpl_vars['row']['status_display'];  echo '</td><td>';  echo $this->_tpl_vars['row']['action'];  echo '</td></tr>';  endif;  echo '';  endforeach; endif; unset($_from);  echo '</table>'; ?>


    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/pager.tpl", 'smarty_include_vars' => array('location' => 'bottom')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </form>
    </fieldset>
<?php endif; ?>

<?php if ($this->_tpl_vars['history'] != 1): ?>
        <div id="activityHx[show]" class="data-group">
        <?php if ($this->_tpl_vars['totalCountActivity']): ?>
            <a href="<?php echo CRM_Utils_System::crmURL(array('p' => 'civicrm/contact/view/activity','q' => "show=1&action=browse&history=1&cid=".($this->_tpl_vars['contactId'])), $this);?>
"><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/TreePlus.gif" class="action-icon" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>open section<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>"/></a><label><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Activity History<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></label> (<?php echo $this->_tpl_vars['totalCountActivity']; ?>
)
        <?php else: ?>
            <dl><dt><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Activity History<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dt><dd><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>No activity history for this contact.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd></dl>
        <?php endif; ?>
    </div>
<?php endif; ?>