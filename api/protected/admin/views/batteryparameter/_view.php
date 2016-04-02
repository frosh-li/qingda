<?php
/* @var $this BatteryparameterController */
/* @var $data BatteryParameter */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('CurrentAdjust_KV')); ?>:</b>
	<?php echo CHtml::encode($data->CurrentAdjust_KV); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TemperatureAdjust_KT')); ?>:</b>
	<?php echo CHtml::encode($data->TemperatureAdjust_KT); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('T0_ADC')); ?>:</b>
	<?php echo CHtml::encode($data->T0_ADC); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('T0_Temperature')); ?>:</b>
	<?php echo CHtml::encode($data->T0_Temperature); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('T1_ADC')); ?>:</b>
	<?php echo CHtml::encode($data->T1_ADC); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('T1_Temperature')); ?>:</b>
	<?php echo CHtml::encode($data->T1_Temperature); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Rin_Span')); ?>:</b>
	<?php echo CHtml::encode($data->Rin_Span); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('OSC')); ?>:</b>
	<?php echo CHtml::encode($data->OSC); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('BatteryU_H')); ?>:</b>
	<?php echo CHtml::encode($data->BatteryU_H); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('BaterryU_L')); ?>:</b>
	<?php echo CHtml::encode($data->BaterryU_L); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Electrode_T_H_Limit')); ?>:</b>
	<?php echo CHtml::encode($data->Electrode_T_H_Limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Electrode_T_L_Limit')); ?>:</b>
	<?php echo CHtml::encode($data->Electrode_T_L_Limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Rin_High_Limit')); ?>:</b>
	<?php echo CHtml::encode($data->Rin_High_Limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Rin_Adjust_KR')); ?>:</b>
	<?php echo CHtml::encode($data->Rin_Adjust_KR); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PreAmp_KA')); ?>:</b>
	<?php echo CHtml::encode($data->PreAmp_KA); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Rin_ExciteI_KI')); ?>:</b>
	<?php echo CHtml::encode($data->Rin_ExciteI_KI); ?>
	<br />

	*/ ?>

</div>