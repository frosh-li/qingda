<?php
/* @var $this AdminuserController */
/* @var $model Adminuser */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('添加后台用户'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>添加用户</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>