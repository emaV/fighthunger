<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 07:56:49
         compiled from CRM/Contact/Form/CommPrefs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Contact/Form/CommPrefs.tpl', 5, false),)), $this); ?>

<div id="commPrefs">
<fieldset><legend><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Communication Preferences<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></legend>
	<table class="form-layout">
    <tr>
		<td><?php echo $this->_tpl_vars['form']['privacy']['label']; ?>
</td>
        <td><?php echo $this->_tpl_vars['form']['privacy']['html']; ?>
</td>
    </tr>
    <tr>
        <td><?php echo $this->_tpl_vars['form']['preferred_communication_method']['label']; ?>
</td>
        <td>
            <?php echo $this->_tpl_vars['form']['preferred_communication_method']['html']; ?>

            <div class="description font-italic">
                <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Select the preferred method of communicating with this contact.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
            </div>
        </td>
    </tr>
    </table>
</fieldset>
</div>