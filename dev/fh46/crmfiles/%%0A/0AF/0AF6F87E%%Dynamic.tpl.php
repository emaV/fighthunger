<?php /* Smarty version 2.6.12-dev, created on 2006-04-17 13:52:09
         compiled from CRM/Profile/Form/Dynamic.tpl */ ?>
<?php if (! empty ( $this->_tpl_vars['fields'] )): ?>
<div id="crm-container"> 
<?php if ($this->_tpl_vars['mode'] == 8 || $this->_tpl_vars['mode'] == 1):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CRM/common/form_body.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
    <?php echo '';  if ($this->_tpl_vars['help_pre'] && $this->_tpl_vars['action'] != 4):  echo '<div class="messages help">';  echo $this->_tpl_vars['help_pre'];  echo '</div>';  endif;  echo '';  $this->assign('zeroField', 'Initial Non Existent Fieldset');  echo '';  $this->assign('fieldset', $this->_tpl_vars['zeroField']);  echo '';  $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['field']):
 echo '';  if ($this->_tpl_vars['field']['groupTitle'] != $this->_tpl_vars['fieldset']):  echo '';  if ($this->_tpl_vars['fieldset'] != $this->_tpl_vars['zeroField']):  echo '</table>';  if ($this->_tpl_vars['groupHelpPost']):  echo '<div class="messages help">';  echo $this->_tpl_vars['groupHelpPost'];  echo '</div>';  endif;  echo '';  if ($this->_tpl_vars['mode'] != 8):  echo '</fieldset>';  endif;  echo '';  endif;  echo '';  if ($this->_tpl_vars['mode'] != 8):  echo '<fieldset><legend>';  echo $this->_tpl_vars['field']['groupTitle'];  echo '</legend>';  endif;  echo '';  $this->assign('fieldset', ($this->_tpl_vars['field']['groupTitle']));  echo '';  $this->assign('groupHelpPost', ($this->_tpl_vars['field']['groupHelpPost']));  echo '';  if ($this->_tpl_vars['field']['groupHelpPre']):  echo '<div class="messages help">';  echo $this->_tpl_vars['field']['groupHelpPre'];  echo '</div>';  endif;  echo '<table class="form-layout-compressed">';  endif;  echo '';  $this->assign('n', $this->_tpl_vars['field']['name']);  echo '';  if ($this->_tpl_vars['field']['options_per_line'] > 1):  echo '<tr><td class="option-label">';  echo $this->_tpl_vars['form'][$this->_tpl_vars['n']]['label'];  echo '</td><td>';  $this->assign('count', '1');  echo '';  echo '<table class="form-layout-compressed"><tr>';  echo '';  $this->assign('index', '1');  echo '';  $_from = $this->_tpl_vars['form'][$this->_tpl_vars['n']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['outer']['iteration']++;
 echo '';  if ($this->_tpl_vars['index'] < 10):  echo '';  $this->assign('index', ($this->_tpl_vars['index']+1));  echo '';  else:  echo '<td class="labels font-light">';  echo $this->_tpl_vars['form'][$this->_tpl_vars['n']][$this->_tpl_vars['key']]['html'];  echo '</td>';  if ($this->_tpl_vars['count'] == $this->_tpl_vars['field']['options_per_line']):  echo '</tr><tr>';  $this->assign('count', '1');  echo '';  else:  echo '';  $this->assign('count', ($this->_tpl_vars['count']+1));  echo '';  endif;  echo '';  endif;  echo '';  endforeach; endif; unset($_from);  echo '</tr></table>';  echo '</td></tr>';  else:  echo '<tr><td class="label">';  echo $this->_tpl_vars['form'][$this->_tpl_vars['n']]['label'];  echo '</td><td>';  echo $this->_tpl_vars['form'][$this->_tpl_vars['n']]['html'];  echo '</td></tr>';  endif;  echo '';  echo '';  if ($this->_tpl_vars['field']['help_post'] && $this->_tpl_vars['action'] != 4):  echo '<tr><td>&nbsp;</td><td class="description">';  echo $this->_tpl_vars['field']['help_post'];  echo '</td></tr>';  endif;  echo '';  endforeach; endif; unset($_from);  echo '</table>';  if ($this->_tpl_vars['field']['groupHelpPost']):  echo '<div class="messages help">';  echo $this->_tpl_vars['field']['groupHelpPost'];  echo '</div>';  endif;  echo '';  if ($this->_tpl_vars['mode'] == 4):  echo '<div class="crm-submit-buttons">';  echo $this->_tpl_vars['form']['buttons']['html'];  echo '</div>';  endif;  echo '';  if ($this->_tpl_vars['mode'] != 8):  echo '</fieldset>';  endif;  echo '';  if ($this->_tpl_vars['help_post'] && $this->_tpl_vars['action'] != 4):  echo '<br /><div class="messages help">';  echo $this->_tpl_vars['help_post'];  echo '</div>';  endif;  echo ''; ?>


</div> <?php endif; ?> 