<?php
/* @var $this GroupparameterController */
/* @var $data GroupParameter */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('HaveCurrentSensor')); ?>:</b>
	<?php echo CHtml::encode($data->HaveCurrentSensor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('StationCurrentSensorSpan')); ?>:</b>
	<?php echo CHtml::encode($data->StationCurrentSensorSpan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('StationCurrentSensorZeroADCode')); ?>:</b>
	<?php echo CHtml::encode($data->StationCurrentSensorZeroADCode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('OSC')); ?>:</b>
	<?php echo CHtml::encode($data->OSC); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DisbytegeCurrentLimit')); ?>:</b>
	<?php echo CHtml::encode($data->DisbytegeCurrentLimit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bytegeCurrentLimit')); ?>:</b>
	<?php echo CHtml::encode($data->bytegeCurrentLimit); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('TemperatureHighLimit')); ?>:</b>
	<?php echo CHtml::encode($data->TemperatureHighLimit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TemperatureLowLimit')); ?>:</b>
	<?php echo CHtml::encode($data->TemperatureLowLimit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('HumiH')); ?>:</b>
	<?php echo CHtml::encode($data->HumiH); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('HumiL')); ?>:</b>
	<?php echo CHtml::encode($data->HumiL); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TemperatureAdjust')); ?>:</b>
	<?php echo CHtml::encode($data->TemperatureAdjust); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('HumiAdjust')); ?>:</b>
	<?php echo CHtml::encode($data->HumiAdjust); ?>
	<br />

	*/ ?>

</div>