<?php
/* @var $this AdmintypeController */
/* @var $model Admintype */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('用户类型管理'=>$this->createUrl('admintype/admin'), '查看类型'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">

<h4>查看用户类型#<?php echo $model->typename; ?></h4>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'role',
		'typename',
		'system',
		'create_time',
		'update_time',
	),
)); ?>
</div>
