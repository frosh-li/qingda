<?php
/* @var $this AboutController */
/* @var $model About */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('单页管理'=>$this->createUrl('about/admin'), '查看关于'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<h4>查看单页#<?php echo $model->id; ?></h4>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'title',
		'content',
	),
)); ?>
