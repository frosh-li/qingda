<?php
/* @var $this StationparameterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Station Parameters',
);

$this->menu=array(
	array('label'=>'Create StationParameter', 'url'=>array('create')),
	array('label'=>'Manage StationParameter', 'url'=>array('admin')),
);
?>

<h1>Station Parameters</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
