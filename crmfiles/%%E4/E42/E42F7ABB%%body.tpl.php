<?php /* Smarty version 2.6.12-dev, created on 2006-04-21 07:56:50
         compiled from CRM/common/calendar/body.tpl */ ?>
<?php if (! $this->_tpl_vars['trigger']): ?>
  <?php $this->assign('trigger', 'trigger');  endif; ?>

<?php echo '
<script type="text/javascript">
    var obj = new Date();
    var currentYear = obj.getFullYear();
'; ?>

<?php if ($this->_tpl_vars['offset']): ?>
    var startYear = currentYear - <?php echo $this->_tpl_vars['offset']; ?>
;
    var endYear = currentYear + <?php echo $this->_tpl_vars['offset']; ?>
;
<?php endif; ?>

<?php echo '
    Calendar.setup(
      {
'; ?>

         dateField   : "<?php echo $this->_tpl_vars['dateVar']; ?>
[d]",
         monthField  : "<?php echo $this->_tpl_vars['dateVar']; ?>
[M]",
         yearField   : "<?php echo $this->_tpl_vars['dateVar']; ?>
[Y]",
<?php if ($this->_tpl_vars['doTime']): ?>
         hourField   : "<?php echo $this->_tpl_vars['dateVar']; ?>
[h]",
         minuteField : "<?php echo $this->_tpl_vars['dateVar']; ?>
[i]",
         ampmField   : "<?php echo $this->_tpl_vars['dateVar']; ?>
[A]",
         showsTime   : true,
         timeFormat  : "12",
<?php endif; ?>
         range       : [<?php echo $this->_tpl_vars['startDate']; ?>
, <?php echo $this->_tpl_vars['endDate']; ?>
],
         button      : "<?php echo $this->_tpl_vars['trigger']; ?>
"
<?php echo '
      }
    );
</script>
'; ?>


