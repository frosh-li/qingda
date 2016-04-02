<?php
/* @var $this StationparameterController */
/* @var $data StationParameter */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Time_interval_Rin')); ?>:</b>
	<?php echo CHtml::encode($data->Time_interval_Rin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Time_interval_U')); ?>:</b>
	<?php echo CHtml::encode($data->Time_interval_U); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('U_abnormal_limit')); ?>:</b>
	<?php echo CHtml::encode($data->U_abnormal_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('T_abnormal_limit')); ?>:</b>
	<?php echo CHtml::encode($data->T_abnormal_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Rin_abnormal_limit')); ?>:</b>
	<?php echo CHtml::encode($data->Rin_abnormal_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('T_upper_limit')); ?>:</b>
	<?php echo CHtml::encode($data->T_upper_limit); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('T_lower_limit')); ?>:</b>
	<?php echo CHtml::encode($data->T_lower_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Humi_upper_limit')); ?>:</b>
	<?php echo CHtml::encode($data->Humi_upper_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Humi_lower_limit')); ?>:</b>
	<?php echo CHtml::encode($data->Humi_lower_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Group_I_criterion')); ?>:</b>
	<?php echo CHtml::encode($data->Group_I_criterion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bytegeStatus_U_upper')); ?>:</b>
	<?php echo CHtml::encode($data->bytegeStatus_U_upper); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bytegeStatus_U_lower')); ?>:</b>
	<?php echo CHtml::encode($data->bytegeStatus_U_lower); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FloatingbytegeStatus_U_upper')); ?>:</b>
	<?php echo CHtml::encode($data->FloatingbytegeStatus_U_upper); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('FloatingbytegeStatus_U_lower')); ?>:</b>
	<?php echo CHtml::encode($data->FloatingbytegeStatus_U_lower); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DisbytegeStatus_U_upper')); ?>:</b>
	<?php echo CHtml::encode($data->DisbytegeStatus_U_upper); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('DisbytegeStatus_U_lower')); ?>:</b>
	<?php echo CHtml::encode($data->DisbytegeStatus_U_lower); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('N_Groups_Incide_Station')); ?>:</b>
	<?php echo CHtml::encode($data->N_Groups_Incide_Station); ?>
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