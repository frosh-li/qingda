<?php
/* @var $this BatteryparameterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Battery Parameters',
);

$this->menu=array(
	array('label'=>'Create BatteryParameter', 'url'=>array('create')),
	array('label'=>'Manage BatteryParameter', 'url'=>array('admin')),
);
?>

<h1>Battery Parameters</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
