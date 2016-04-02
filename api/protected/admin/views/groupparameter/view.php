<?php
/* @var $this GroupparameterController */
/* @var $model GroupParameter */

$this->breadcrumbs=array(
	'Group Parameters'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GroupParameter', 'url'=>array('index')),
	array('label'=>'Create GroupParameter', 'url'=>array('create')),
	array('label'=>'Update GroupParameter', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GroupParameter', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GroupParameter', 'url'=>array('admin')),
);
?>

<h1>View GroupParameter #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'HaveCurrentSensor',
		'StationCurrentSensorSpan',
		'StationCurrentSensorZeroADCode',
		'OSC',
		'DisbytegeCurrentLimit',
		'bytegeCurrentLimit',
		'TemperatureHighLimit',
		'TemperatureLowLimit',
		'HumiH',
		'HumiL',
		'TemperatureAdjust',
		'HumiAdjust',
	),
)); ?>
