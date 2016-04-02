<?php
/* @var $this SysuserController */
/* @var $model Sysuser */

$this->breadcrumbs=array(
	'Sysusers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Sysuser', 'url'=>array('index')),
	array('label'=>'Manage Sysuser', 'url'=>array('admin')),
);
?>

<h1>Create Sysuser</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>