<?php /* Smarty version 2.6.12-dev, created on 2006-05-15 03:22:04
         compiled from CRM/Profile/Page/Listings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Profile/Page/Listings.tpl', 12, false),array('function', 'counter', 'CRM/Profile/Page/Listings.tpl', 37, false),array('function', 'cycle', 'CRM/Profile/Page/Listings.tpl', 39, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['columnHeaders'] ) || $this->_tpl_vars['isReset']): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Profile/Form/Search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['rows']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/pager.tpl", 'smarty_include_vars' => array('location' => 'top')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php if ($this->_tpl_vars['qill']): ?>
     <p>
     <div id="search-status">
        <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Displaying contacts where:<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        <ul>
        <?php $_from = $this->_tpl_vars['qill']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['criteria']):
?>
          <li><?php echo $this->_tpl_vars['criteria']; ?>
</li>
        <?php endforeach; endif; unset($_from); ?>
        </ul>
     </div>
     </p>
    <?php endif; ?>

    <?php echo '<table><tr class="columnheader">';  $_from = $this->_tpl_vars['columnHeaders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header']):
 echo '<th>';  if ($this->_tpl_vars['header']['sort']):  echo '';  $this->assign('key', $this->_tpl_vars['header']['sort']);  echo '';  echo $this->_tpl_vars['sort']->_response[$this->_tpl_vars['key']]['link'];  echo '';  else:  echo '';  echo $this->_tpl_vars['header']['name'];  echo '';  endif;  echo '</th>';  endforeach; endif; unset($_from);  echo '</tr>';  echo smarty_function_counter(array('start' => 0,'skip' => 1,'print' => false), $this); echo '';  $_from = $this->_tpl_vars['rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
 echo '<tr id=\'rowid_';  echo $this->_tpl_vars['row']['contact_id'];  echo '\' class="';  echo smarty_function_cycle(array('values' => "odd-row,even-row"), $this); echo '">';  $_from = $this->_tpl_vars['row']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
 echo '<td>';  echo $this->_tpl_vars['value'];  echo '</td>';  endforeach; endif; unset($_from);  echo '</tr>';  endforeach; endif; unset($_from);  echo '</table>'; ?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/pager.tpl", 'smarty_include_vars' => array('location' => 'bottom')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif (! $this->_tpl_vars['isReset']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Contact/Form/Search/EmptyResults.tpl", 'smarty_include_vars' => array('context' => 'Profile')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>


<?php else: ?>
    <div class="messages status">
      <dl>
        <dt><img src="<?php echo $this->_tpl_vars['config']->resourceBase; ?>
i/Inform.gif" alt="<?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>status<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>" /></dt>
        <dd><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>No fields in this Profile have been configured to display as columns in the listings (selector) table. Ask the site administrator to check the Profile setup.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
      </dl>
    </div>
<?php endif; ?>