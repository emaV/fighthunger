<?php /* Smarty version 2.6.12-dev, created on 2006-05-17 06:10:44
         compiled from CRM/Activity/Form/Phonecall.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Activity/Form/Phonecall.tpl', 8, false),array('modifier', 'crmDate', 'CRM/Activity/Form/Phonecall.tpl', 24, false),array('modifier', 'crmReplace', 'CRM/Activity/Form/Phonecall.tpl', 39, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/calendar/js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div class="form-item">
  <fieldset>
   <legend>
    <?php if ($this->_tpl_vars['action'] == 1): ?>
        <?php if ($this->_tpl_vars['log']):  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Log a Phone Call<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  else:  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Schedule a Phone Call<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  endif; ?>
    <?php elseif ($this->_tpl_vars['action'] == 2):  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Edit Scheduled Call<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
    <?php elseif ($this->_tpl_vars['action'] == 8):  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Delete Phone Call<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
    <?php else:  $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>View Scheduled Call<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack);  endif; ?>
  </legend>
  <dl>
     <?php if ($this->_tpl_vars['action'] == 1 || $this->_tpl_vars['action'] == 2 || $this->_tpl_vars['action'] == 4): ?>	
        <?php if ($this->_tpl_vars['action'] == 1): ?>
          <dt><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>With Contact<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dt><dd><?php echo $this->_tpl_vars['displayName']; ?>
&nbsp;</dd>
        <?php else: ?>
  	  <dt><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>With Contact<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dt><dd><?php echo $this->_tpl_vars['targetName']; ?>
&nbsp;</dd>
	  <dt><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Created By<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dt><dd><?php echo $this->_tpl_vars['sourceName']; ?>
&nbsp;</dd>
        <?php endif; ?>
	<dt><?php echo $this->_tpl_vars['form']['subject']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['subject']['html']; ?>
</dd>
	<dt><?php echo $this->_tpl_vars['form']['phone_id']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['phone_id']['html'];  if ($this->_tpl_vars['action'] != 4): ?>&nbsp;<?php echo $this->_tpl_vars['form']['phone_number']['label']; ?>
&nbsp;<?php endif;  echo $this->_tpl_vars['form']['phone_number']['html']; ?>
</dd>
    <?php if ($this->_tpl_vars['action'] == 4): ?>
        <dt><?php echo $this->_tpl_vars['form']['scheduled_date_time']['label']; ?>
</dt><dd><?php echo ((is_array($_tmp=$this->_tpl_vars['scheduled_date_time'])) ? $this->_run_mod_handler('crmDate', true, $_tmp) : smarty_modifier_crmDate($_tmp)); ?>
</dd>
    <?php else: ?>
        <dt><?php echo $this->_tpl_vars['form']['scheduled_date_time']['label']; ?>
</dt>
        <dd><?php echo $this->_tpl_vars['form']['scheduled_date_time']['html']; ?>
</dd>
        <dt>&nbsp;</dt>
        <dd class="description">
               <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/calendar/desc.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </dd>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/calendar/body.tpl", 'smarty_include_vars' => array('dateVar' => 'scheduled_date_time','startDate' => 'currentYear','endDate' => 'endYear','offset' => 3,'doTime' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
	<dt><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Duration<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dt><dd><?php echo $this->_tpl_vars['form']['duration_hours']['html']; ?>
 <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Hrs<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?> &nbsp; <?php echo $this->_tpl_vars['form']['duration_minutes']['html']; ?>
 <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Min<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?> &nbsp;</dd>
	<dt><?php echo $this->_tpl_vars['form']['status']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['status']['html']; ?>
</dd>
    <?php if ($this->_tpl_vars['action'] != 4): ?>
        <dt>&nbsp;</dt><dd class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Call will be moved to Activity History when status is 'Completed'.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
    <?php endif; ?>
	<dt><?php echo $this->_tpl_vars['form']['details']['label']; ?>
</dt><dd><?php echo ((is_array($_tmp=$this->_tpl_vars['form']['details']['html'])) ? $this->_run_mod_handler('crmReplace', true, $_tmp, 'class', 'huge') : smarty_modifier_crmReplace($_tmp, 'class', 'huge')); ?>
&nbsp;</dd>
    

    <dt><?php echo $this->_tpl_vars['form']['is_active']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['is_active']['html']; ?>
</dd>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Contact/Page/View/CustomData.tpl", 'smarty_include_vars' => array('mainEditForm' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>			
<?php endif; ?>
    <?php if ($this->_tpl_vars['action'] == 8): ?>
    <div class="status"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?> Are you sure you want to delete "<?php echo $this->_tpl_vars['delName']; ?>
" ?<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></div>
    <?php endif; ?>	
    <dt></dt><dd><?php echo $this->_tpl_vars['form']['buttons']['html']; ?>
</dd>
    
  </dl>
</fieldset>
</div>