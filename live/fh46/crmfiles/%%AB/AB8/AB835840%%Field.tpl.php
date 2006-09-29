<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 06:01:50
         compiled from CRM/Custom/Form/Field.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Custom/Form/Field.tpl', 63, false),array('modifier', 'crmReplace', 'CRM/Custom/Form/Field.tpl', 81, false),array('function', 'crmURL', 'CRM/Custom/Form/Field.tpl', 126, false),)), $this); ?>
<?php echo '
<script type="text/Javascript">
    function custom_option_html_type(form) { 
        var html_type = document.getElementsByName("data_type[1]")[0];
        var html_type_name = html_type.options[html_type.selectedIndex].value;
        var data_type = document.getElementsByName("data_type[0]")[0];
        if (data_type.selectedIndex < 4) {
            if (html_type_name != "Text") {
	    document.getElementById("showoption").style.display="block";		
            document.getElementById("hideDefaultValTxt").style.display="none";
            document.getElementById("hideDefaultValDef").style.display="none";
            document.getElementById("hideDescTxt").style.display="none";
            document.getElementById("hideDescDef").style.display="none";
            } else {
	    document.getElementById("showoption").style.display="none";
	    document.getElementById("showoption").style.display="none";
            document.getElementById("hideDefaultValTxt").style.display="block";
            document.getElementById("hideDefaultValDef").style.display="block";
            document.getElementById("hideDescTxt").style.display="block";
            document.getElementById("hideDescDef").style.display="block";
            }
        } else {
	    document.getElementById("showoption").style.display="none";
            document.getElementById("hideDefaultValTxt").style.display="block";
            document.getElementById("hideDefaultValDef").style.display="block";
            document.getElementById("hideDescTxt").style.display="block";
            document.getElementById("hideDescDef").style.display="block";
        }
	
	var radioOption, checkBoxOption;

	for (var i=1; i<=11; i++) {
	    radioOption = \'radio\'+i;
	    checkBoxOption = \'checkbox\'+i	
	    if (data_type.selectedIndex < 4) {
                 if (html_type_name != "Text") {
		     if (html_type_name == "CheckBox" || html_type_name == "Multi-Select") {
	                 document.getElementById(checkBoxOption).style.display="block";
		         document.getElementById(radioOption).style.display="none";
		     } else {
                         document.getElementById(radioOption).style.display="block";	
		         document.getElementById(checkBoxOption).style.display="none";
		     }
		 }
	    }
	}

	if (data_type.selectedIndex < 4) {	
		if (html_type_name == "CheckBox" || html_type_name == "Radio") {
			document.getElementById("optionsPerLine").style.display="block";
			document.getElementById("optionsPerLineDef").style.display="block";
		} else {
			document.getElementById("optionsPerLine").style.display="none";
			document.getElementById("optionsPerLineDef").style.display="none";
		}
	}
			 

    }
</script>
'; ?>

<fieldset><legend><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Custom Data Field<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></legend>

    <div class="form-item">
        <dl>
        <dt><?php echo $this->_tpl_vars['form']['label']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['label']['html']; ?>
</dd>
        <dt><?php echo $this->_tpl_vars['form']['data_type']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['data_type']['html']; ?>
</dd>
        <?php if ($this->_tpl_vars['action'] != 4 && $this->_tpl_vars['action'] != 2): ?>
            <dt>&nbsp;</dt><dd class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Select the type of data you want to collect and store for this contact. Then select from the available HTML input field types (choices are based on the type of data being collected).<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        <?php endif; ?>
        </dl>

    <?php if ($this->_tpl_vars['action'] == 1): ?>
                <div id='showoption' class="hide-block"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/Custom/Form/Optionfields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
    <?php endif; ?>
        
        <dl>
	    <dt id="optionsPerLine" <?php if ($this->_tpl_vars['action'] == 2 && ( $this->_tpl_vars['form']['data_type']['value']['0']['0'] < 4 && $this->_tpl_vars['form']['data_type']['value']['1']['0'] == 'CheckBox' || $this->_tpl_vars['form']['data_type']['value']['1']['0'] == 'Radio' )): ?>class="show-block"<?php else: ?> class="hide-block" <?php endif; ?>><?php echo $this->_tpl_vars['form']['options_per_line']['label']; ?>
</dt>	
	    <dd id="optionsPerLineDef" <?php if ($this->_tpl_vars['action'] == 2 && ( $this->_tpl_vars['form']['data_type']['value']['0']['0'] < 4 && $this->_tpl_vars['form']['data_type']['value']['1']['0'] == 'CheckBox' || $this->_tpl_vars['form']['data_type']['value']['1']['0'] == 'Radio' )): ?>class="show-block"<?php else: ?> class="hide-block"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['form']['options_per_line']['html'])) ? $this->_run_mod_handler('crmReplace', true, $_tmp, 'class', 'two') : smarty_modifier_crmReplace($_tmp, 'class', 'two')); ?>
</dd>
        
        <dt><?php echo $this->_tpl_vars['form']['weight']['label']; ?>
</dt><dd><?php echo ((is_array($_tmp=$this->_tpl_vars['form']['weight']['html'])) ? $this->_run_mod_handler('crmReplace', true, $_tmp, 'class', 'two') : smarty_modifier_crmReplace($_tmp, 'class', 'two')); ?>
</dd>
        <?php if ($this->_tpl_vars['action'] != 4): ?>
        <dt>&nbsp;</dt><dd class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Weight controls the order in which fields are displayed in a group. Enter a positive or negative integer - lower numbers are displayed ahead of higher numbers.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        <?php endif; ?>
        <dt id="hideDefaultValTxt" title="hideDefaultValTxt" <?php if ($this->_tpl_vars['action'] == 2 && ( $this->_tpl_vars['form']['data_type']['value']['0']['0'] < 4 && $this->_tpl_vars['form']['data_type']['value']['1']['0'] != 'Text' )): ?>class="hide-block"<?php endif; ?>><?php echo $this->_tpl_vars['form']['default_value']['label']; ?>
</dt>
        <dd id="hideDefaultValDef" title="hideDefaultValDef" <?php if ($this->_tpl_vars['action'] == 2 && ( $this->_tpl_vars['form']['data_type']['value']['0']['0'] < 4 && $this->_tpl_vars['form']['data_type']['value']['1']['0'] != 'Text' )): ?>class="hide-block"<?php endif; ?>><?php echo $this->_tpl_vars['form']['default_value']['html']; ?>
</dd>
        <?php if ($this->_tpl_vars['action'] != 4): ?>
        <dt id="hideDescTxt" title="hideDescTxt" <?php if ($this->_tpl_vars['action'] == 2 && ( $this->_tpl_vars['form']['data_type']['value']['0']['0'] < 4 && $this->_tpl_vars['form']['data_type']['value']['1']['0'] != 'Text' )): ?>class="hide-block"<?php endif; ?>>&nbsp;</dt>
        <dd id="hideDescDef" title="hideDescDef" <?php if ($this->_tpl_vars['action'] == 2 && ( $this->_tpl_vars['form']['data_type']['value']['0']['0'] < 4 && $this->_tpl_vars['form']['data_type']['value']['1']['0'] != 'Text' )): ?>class="hide-block"<?php endif; ?>><span class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>If you want to provide a default value for this field, enter it here. For date fields, format is YYYY-MM-DD.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></span></dd>
        <?php endif; ?>
        <dt><?php echo $this->_tpl_vars['form']['help_post']['label']; ?>
</dt><dd>&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['form']['help_post']['html'])) ? $this->_run_mod_handler('crmReplace', true, $_tmp, 'class', 'huge') : smarty_modifier_crmReplace($_tmp, 'class', 'huge')); ?>
&nbsp;</dd>
        <?php if ($this->_tpl_vars['action'] != 4): ?>
        <dt>&nbsp;</dt><dd class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Explanatory text displayed to users for this field.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        <?php endif; ?>
        <dt><?php echo $this->_tpl_vars['form']['is_required']['label']; ?>
</dt><dd>&nbsp;<?php echo $this->_tpl_vars['form']['is_required']['html']; ?>
</dd>
	    <dt><?php echo $this->_tpl_vars['form']['is_searchable']['label']; ?>
</dt><dd>&nbsp;<?php echo $this->_tpl_vars['form']['is_searchable']['html']; ?>
</dd>
        <?php if ($this->_tpl_vars['action'] != 4): ?>
        <dt>&nbsp;</dt><dd class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Is this field included in the Advanced Search form? NOTE: This feature is only available to custom fields used for <strong>Contacts</strong> at this time.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        <?php endif; ?>
        <dt><?php echo $this->_tpl_vars['form']['is_active']['label']; ?>
</dt><dd>&nbsp;<?php echo $this->_tpl_vars['form']['is_active']['html']; ?>
</dd>
        </dl>
    </div>
    
    <div id="crm-submit-buttons" class="form-item">
    <dl>
    <?php if ($this->_tpl_vars['action'] != 4): ?>
        <dt>&nbsp;</dt><dd><?php echo $this->_tpl_vars['form']['buttons']['html']; ?>
</dd>
    <?php else: ?>
        <dt>&nbsp;</dt><dd><?php echo $this->_tpl_vars['form']['done']['html']; ?>
</dd>
    <?php endif; ?>     </dl>
    </div>

</fieldset>
	
<script type="text/javascript">
	<?php if (( $this->_tpl_vars['optionRowError'] || $this->_tpl_vars['fieldError'] ) && $this->_tpl_vars['action'] == 1): ?>
         custom_option_html_type(document.getElementById('Field'));
	<?php endif; ?>
	</script>
<?php if ($this->_tpl_vars['action'] == 2 && ( $this->_tpl_vars['form']['data_type']['value']['1']['0'] == 'CheckBox' || $this->_tpl_vars['form']['data_type']['value']['1']['0'] == 'Radio' || $this->_tpl_vars['form']['data_type']['value']['1']['0'] == 'Select' || $this->_tpl_vars['form']['data_type']['value']['1']['0'] == 'Multi-Select' )): ?>
    <div class="action-link">
        <a href="<?php echo CRM_Utils_System::crmURL(array('p' => "civicrm/admin/custom/group/field/option",'q' => "reset=1&action=browse&fid=".($this->_tpl_vars['id'])), $this);?>
">&raquo; <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>View / Edit Multiple Choice Options<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></a>
    </div>
<?php endif; ?>