<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 05:56:37
         compiled from CRM/Import/Form/UploadFile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'ts', 'CRM/Import/Form/UploadFile.tpl', 9, false),)), $this); ?>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/WizardHeader.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 
 <div id="help">
    <p>
    <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>The Import Wizard allows you to easily upload contact records from other applications into CiviCRM. For example, if your organization has contacts in MS Access&copy; or Excel&copy;, and you want to start using CiviCRM to store these contacts, you can 'import' them here.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
    </p>
    <p>
    <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Files to be imported must be in the 'comma-separated-values' format (CSV). Most applications will allow you to export records in this format. Consult the documentation for your application if you're not sure how to do this. Save this file to your local hard drive (or an accessible drive on your network) - and you are now ready for step 1 (Upload Data).<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
    </p>
 </div>    

 <div id="upload-file" class="form-item">
 <fieldset><legend><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Upload Data File<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></legend>
    <dl>
        <dt><?php echo $this->_tpl_vars['form']['uploadFile']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['uploadFile']['html']; ?>
</dd>
        <dt>&nbsp;</dt>
        <dd class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>File format must be comma-separated-values (CSV).<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        <dt>&nbsp;</dt>
	    <dd><?php $this->_tag_stack[] = array('ts', array('1' => $this->_tpl_vars['uploadSize'])); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Maximum Upload File Size: %1 MB<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?></dd>
        <dt> </dt><dd><?php echo $this->_tpl_vars['form']['skipColumnHeader']['html']; ?>
 <?php echo $this->_tpl_vars['form']['skipColumnHeader']['label']; ?>
</dd>
        <dt>&nbsp;</dt>
        <dd class="description">
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Check this box if the first row of your file consists of field names (Example: "First Name","Last Name","Email")<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        </dd> 
        <dt><?php echo $this->_tpl_vars['form']['contactType']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['contactType']['html']; ?>
</dd>
        <dt>&nbsp;</dt>
        <dd class="description">
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Select 'Individual' if each record in your file represents and individual person - even if the file also contains related Organization data (e.g. Employer Name, Employer Address, etc.).<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        </dd>
        <dt>&nbsp;</dt>
        <dd class="description"><?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>Select 'Organization' or 'Household' if each record in your file represents a contact of that type.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        </dd> 
        <dt><?php echo $this->_tpl_vars['form']['onDuplicate']['label']; ?>
</dt><dd><?php echo $this->_tpl_vars['form']['onDuplicate']['html']; ?>
</dd>
        <dt>&nbsp;</dt>
        <dd class="description">
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>If a contact in the import file appears to be a duplicate of an existing CiviCRM contact...<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        </dd>
        <dt>&nbsp;</dt>
        <dd class="description">
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?><label>Skip:</label> Reports and then Skips duplicate import file rows - leaving the matching record in the database as-is (default).<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        </dd>
        <dt>&nbsp;</dt>
        <dd class="description">
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?><label>Update:</label> Updates database fields with available import data. Fields in the database which are NOT included in the import row are left as-is.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        </dd>
        <dt>&nbsp;</dt>
        <dd class="description">
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?><label>Fill:</label> Fills in additional contact data only. Database fields which currently have values are left as-is.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        </dd>
        <dt>&nbsp;</dt>
        <dd class="description">
            <?php $this->_tag_stack[] = array('ts', array()); smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?><label>No Duplicate Checking:</label> Insert all valid records without comparing them to existing contact records for possible duplicates.<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_ts($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
        </dd>
    </dl>
 </fieldset>
 </div>
 <div id="crm-submit-buttons">
    <?php echo $this->_tpl_vars['form']['buttons']['html']; ?>

 </div>