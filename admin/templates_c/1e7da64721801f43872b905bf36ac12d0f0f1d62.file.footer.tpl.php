<?php /* Smarty version Smarty-3.1.13, created on 2017-05-15 10:42:45
         compiled from "/home/yves/www/sio2/peda/templates/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:105944360959196a0579bb59-58544168%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e7da64721801f43872b905bf36ac12d0f0f1d62' => 
    array (
      0 => '/home/yves/www/sio2/peda/templates/footer.tpl',
      1 => 1478954140,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '105944360959196a0579bb59-58544168',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'identification' => 0,
    'executionTime' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_59196a057a4c77_05025290',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59196a057a4c77_05025290')) {function content_59196a057a4c77_05025290($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/yves/www/sio2/peda/smarty/plugins/modifier.date_format.php';
?><div style="padding-bottom: 60px"></div>
<div class="hidden-print navbar-xs navbar-default navbar-fixed-bottom" style="padding-top:10px">
	<span class="hidden-xs">
		Le <?php echo smarty_modifier_date_format(time(),"%A, %e %b %Y");?>
 à <?php echo smarty_modifier_date_format(time(),"%Hh%M");?>

		Adresse IP: <strong><?php echo $_smarty_tpl->tpl_vars['identification']->value['ip'];?>
</strong> <?php echo $_smarty_tpl->tpl_vars['identification']->value['hostname'];?>

		Votre passage est enregistré
			<span id="execTime"><?php if ($_smarty_tpl->tpl_vars['executionTime']->value){?>Temps d'exécution du script: <?php echo $_smarty_tpl->tpl_vars['executionTime']->value;?>
s<?php }?></span>
	</span>

	<span class="visible-xs">
		<?php echo $_smarty_tpl->tpl_vars['identification']->value['ip'];?>
 <?php echo $_smarty_tpl->tpl_vars['identification']->value['hostname'];?>
 <?php echo smarty_modifier_date_format(time(),"%A, %e %b %Y");?>
 <?php echo smarty_modifier_date_format(time(),"%Hh%M");?>

	</span>

</div>  <!-- navbar -->
<?php }} ?>