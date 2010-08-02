<?php /* Smarty version 2.6.26, created on 2010-08-02 17:31:05
         compiled from index.php */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'index.php', 3, false),)), $this); ?>
Welcome to Spine PHP.<br /><br />
You have successfully installed Spine, this is just an example page.<br /><br />
<?php echo smarty_function_counter(array('start' => $this->_tpl_vars['i'],'skip' => 1), $this);?>
<br />
<?php echo smarty_function_counter(array(), $this);?>
<br />
<?php echo smarty_function_counter(array(), $this);?>
<br />
<?php echo smarty_function_counter(array(), $this);?>
<br />
<?php echo smarty_function_counter(array(), $this);?>
<br />