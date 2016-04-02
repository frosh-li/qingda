<?php
/* @var $this ChannelsController */
/* @var $model Channels */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('频道管理'=>$this->createUrl('channels/admin'), '查看频道'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>查看频道-<?php echo $model->title; ?></h4>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
            'name'=>'pid',
            'type'=>'raw',
            'value' =>$model->getPidText(),
        ),
		'alias',
		'title',
		'content',
		'seotitle',
		'metakeywords',
		'metadesc',
		'link',
		array(
            'name'=>'target',
            'type'=>'raw',
            'value' =>$model->target ? '是':'否',
        ),
	),
)); ?>
</div>