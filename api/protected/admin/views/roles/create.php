<?php
/* @var $this RolesController */
/* @var $model Roles */

$this->breadcrumbs=array(
	'Roles'=>array('index'),
	'Create',
);
?>

<div class="iframaincontent">
<h4>添加用户栏目权限</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model,'channel'=>$channel)); ?>
</div>