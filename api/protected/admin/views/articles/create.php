<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>
<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('文章管理'=>$this->createUrl('articles/admin'), '添加文章'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>添加文章</h4>
<?php echo $this->renderPartial('_form', array('model'=>$model,'channel'=>$channel)); ?>
</div>