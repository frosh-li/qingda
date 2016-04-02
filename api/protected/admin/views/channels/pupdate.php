<?php
/* @var $this ChannelsController */
/* @var $model Channels */

$this->pageTitle=Yii::app()->name;
?>
<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('更新栏目'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>更新栏目：<?php echo $model->title; ?></h4>

<?php echo $this->renderPartial('_pform', array('model'=>$model)); ?>
</div>