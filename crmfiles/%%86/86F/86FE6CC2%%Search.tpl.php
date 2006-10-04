<?php /* Smarty version 2.6.12-dev, created on 2006-05-15 03:22:04
         compiled from CRM/Profile/Form/Search.tpl */ ?>
<?php if (! empty ( $this->_tpl_vars['fields'] )): ?>
<p>
    <table class="form-layout-compressed">
    <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['field']):
?>
        <?php $this->assign('n', $this->_tpl_vars['field']['name']); ?>
        <tr>
            <td class="label"><?php echo $this->_tpl_vars['form'][$this->_tpl_vars['n']]['label']; ?>
</td>
            <td class="description"><?php echo $this->_tpl_vars['form'][$this->_tpl_vars['n']]['html']; ?>
</td>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
    <tr><td></td><td><?php echo $this->_tpl_vars['form']['buttons']['html']; ?>
</td></tr>
    </table>
</p>
<?php endif; ?>