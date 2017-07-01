<?php
/* @var $this ChannelsController */
/* @var $data Channels */
$this->pageTitle=Yii::app()->name;
?>
<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('添加二级栏目'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>添加二级栏目</h4>
<?php echo $this->renderPartial('_sform', array('model'=>$model)); ?>
</div>