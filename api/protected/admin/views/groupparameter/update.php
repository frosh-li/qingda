<?php
/* @var $this GroupparameterController */
/* @var $model GroupParameter */

$this->breadcrumbs=array(
	'Group Parameters'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GroupParameter', 'url'=>array('index')),
	array('label'=>'Create GroupParameter', 'url'=>array('create')),
	array('label'=>'View GroupParameter', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GroupParameter', 'url'=>array('admin')),
);
?>

<h1>Update GroupParameter <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>