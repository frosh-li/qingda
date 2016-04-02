<?php
/* @var $this AdmintypeController */
/* @var $model Admintype */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('用户类型管理'=>$this->createUrl('adminuser/admin'), '用户类型管理'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>添加用户类型</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>