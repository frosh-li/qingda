<?php
/* @var $this AdmintypeController */
/* @var $model Admintype */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('用户类型管理'=>$this->createUrl('admintype/admin'), '修改用户类型'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>修改用户类型# <?php echo $model->typename; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>