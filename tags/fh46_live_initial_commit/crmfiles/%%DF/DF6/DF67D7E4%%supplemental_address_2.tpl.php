<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 07:56:50
         compiled from CRM/Contact/Form/Address/supplemental_address_2.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Contact/Form/Address/supplemental_address_2.tpl', 9, false),)), $this); ?>
<div class="form-item">
    <span class="labels">
    <?php echo $this->_tpl_vars['form']['location'][$this->_tpl_vars['index']]['address']['supplemental_address_2']['label']; ?>

    </span>
    <span class="fields">
    <?php echo $this->_tpl_vars['form']['location'][$this->_tpl_vars['index']]['address']['supplemental_address_2']['html']; ?>

        <br class="spacer"/>
    <span class="description font-italic"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Supplemental address info, e.g. c/o, department name, building name, etc.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></span>
    </span>
</div>