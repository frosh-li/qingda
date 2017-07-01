<?php
/* @var $this SysuserController */
/* @var $model Sysuser */

$this->breadcrumbs=array(
	'Sysusers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Sysuser', 'url'=>array('index')),
	array('label'=>'Create Sysuser', 'url'=>array('create')),
	array('label'=>'View Sysuser', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Sysuser', 'url'=>array('admin')),
);
?>

<h1>Update Sysuser <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>