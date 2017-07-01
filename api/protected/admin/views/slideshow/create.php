<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>
<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('添加焦点图'),
		  'homeLink'=>CHtml::link('菜单',Yii::app()->homeUrl),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>添加图片广告</h4>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>