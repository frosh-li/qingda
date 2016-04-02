<?php
/* @var $this BatteryparameterController */
/* @var $model BatteryParameter */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CurrentAdjust_KV'); ?>
		<?php echo $form->textField($model,'CurrentAdjust_KV'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TemperatureAdjust_KT'); ?>
		<?php echo $form->textField($model,'TemperatureAdjust_KT'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'T0_ADC'); ?>
		<?php echo $form->textField($model,'T0_ADC'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'T0_Temperature'); ?>
		<?php echo $form->textField($model,'T0_Temperature'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'T1_ADC'); ?>
		<?php echo $form->textField($model,'T1_ADC'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'T1_Temperature'); ?>
		<?php echo $form->textField($model,'T1_Temperature'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Rin_Span'); ?>
		<?php echo $form->textField($model,'Rin_Span'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'OSC'); ?>
		<?php echo $form->textField($model,'OSC'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'BatteryU_H'); ?>
		<?php echo $form->textField($model,'BatteryU_H'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'BaterryU_L'); ?>
		<?php echo $form->textField($model,'BaterryU_L'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Electrode_T_H_Limit'); ?>
		<?php echo $form->textField($model,'Electrode_T_H_Limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Electrode_T_L_Limit'); ?>
		<?php echo $form->textField($model,'Electrode_T_L_Limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Rin_High_Limit'); ?>
		<?php echo $form->textField($model,'Rin_High_Limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Rin_Adjust_KR'); ?>
		<?php echo $form->textField($model,'Rin_Adjust_KR'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PreAmp_KA'); ?>
		<?php echo $form->textField($model,'PreAmp_KA'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Rin_ExciteI_KI'); ?>
		<?php echo $form->textField($model,'Rin_ExciteI_KI'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->