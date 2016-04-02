<?php
/* @var $this LinksController */
/* @var $model Links */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('链接管理'=>$this->createUrl('links/admin'), '查看链接'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>查看链接#<?php echo $model->id; ?></h4>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'url',
		'content',
		'logo',
		'ordernum',
	),
)); ?>
</div>
