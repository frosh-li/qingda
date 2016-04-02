<?php
/* @var $this AboutController */
/* @var $model About */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array($model->title),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>修改#<?php echo $model->title; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>