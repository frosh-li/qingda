<?php
/* @var $this StationparameterController */
/* @var $model StationParameter */

$this->breadcrumbs=array(
	'Station Parameters'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StationParameter', 'url'=>array('index')),
	array('label'=>'Create StationParameter', 'url'=>array('create')),
	array('label'=>'Update StationParameter', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StationParameter', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StationParameter', 'url'=>array('admin')),
);
?>

<h1>View StationParameter #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'Time_interval_Rin',
		'Time_interval_U',
		'U_abnormal_limit',
		'T_abnormal_limit',
		'Rin_abnormal_limit',
		'T_upper_limit',
		'T_lower_limit',
		'Humi_upper_limit',
		'Humi_lower_limit',
		'Group_I_criterion',
		'bytegeStatus_U_upper',
		'bytegeStatus_U_lower',
		'FloatingbytegeStatus_U_upper',
		'FloatingbytegeStatus_U_lower',
		'DisbytegeStatus_U_upper',
		'DisbytegeStatus_U_lower',
		'N_Groups_Incide_Station',
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
