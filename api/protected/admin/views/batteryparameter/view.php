<?php
/* @var $this BatteryparameterController */
/* @var $model BatteryParameter */

$this->breadcrumbs=array(
	'Battery Parameters'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List BatteryParameter', 'url'=>array('index')),
	array('label'=>'Create BatteryParameter', 'url'=>array('create')),
	array('label'=>'Update BatteryParameter', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete BatteryParameter', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BatteryParameter', 'url'=>array('admin')),
);
?>

<h1>View BatteryParameter #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'CurrentAdjust_KV',
		'TemperatureAdjust_KT',
		'T0_ADC',
		'T0_Temperature',
		'T1_ADC',
		'T1_Temperature',
		'Rin_Span',
		'OSC',
		'BatteryU_H',
		'BaterryU_L',
		'Electrode_T_H_Limit',
		'Electrode_T_L_Limit',
		'Rin_High_Limit',
		'Rin_Adjust_KR',
		'PreAmp_KA',
		'Rin_ExciteI_KI',
	),
)); ?>
