<?php
/* @var $this AboutController */
/* @var $model About */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('关于页面管理'=>$this->createUrl('about/admin'), '添加用户'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<h4>创建关于页面</h4>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>