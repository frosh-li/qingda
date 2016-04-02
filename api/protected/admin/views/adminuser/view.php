<?php
/* @var $this AdminuserController */
/* @var $model Adminuser */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('设置管理'=>$this->createUrl('adminuser/admin'), '查看用户'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">

<h4>后台用户管理 #<?php echo $model->username; ?></h4>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'role',
		'email',
		'profile',
	),
)); ?>
</div>