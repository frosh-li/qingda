<?php
/* @var $this ArticlesController */
/* @var $model Articles */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('文章管理'=>$this->createUrl('about/admin'), '查看文章'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>查看文章#<?php echo $model->id; ?></h4>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'channelid',
		'type',
		'cate',
		'hits',
		'posttime',
		'picid',
		'picpath',
		'alias',
		'title',
		'content:html',
		'tag',
		'seotitle',
		'metakeywords',
		'metadesc',
	),
)); ?>
</div>