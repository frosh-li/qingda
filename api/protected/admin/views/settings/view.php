<?php
/* @var $this SettingsController */
/* @var $model Settings */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('设置管理'=>$this->createUrl('settings/admin'), '查看设置'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>查看设置#<?php echo $model->id; ?></h4>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'categoryid',
		'property',
		'setvalue',
		'name',
		'description',
		'langid',
	),
)); ?>
</div>