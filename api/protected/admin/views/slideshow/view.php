<?php
/* @var $this SlideshowController */
/* @var $model Slideshow */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('幻灯片管理'=>$this->createUrl('slideshow/admin'), '查看幻灯片'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>查看幻灯片#<?php echo $model->id; ?></h4>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'url',
		'image',
		'token',
		'sortnum',
		'created',
	),
)); ?>
</div>