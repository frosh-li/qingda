<?php
/* @var $this ChannelsController */
/* @var $data Channels */
$this->pageTitle=Yii::app()->name;
?>
<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('栏目管理'=>$this->createUrl('channels/admin'), '添加栏目'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>添加三级栏目</h4>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>