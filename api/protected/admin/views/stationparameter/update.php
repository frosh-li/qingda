<?php
/* @var $this StationparameterController */
/* @var $model StationParameter */

$this->breadcrumbs=array(
	'Station Parameters'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List StationParameter', 'url'=>array('index')),
	array('label'=>'Create StationParameter', 'url'=>array('create')),
	array('label'=>'View StationParameter', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StationParameter', 'url'=>array('admin')),
);
?>

<h1>Update StationParameter <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>