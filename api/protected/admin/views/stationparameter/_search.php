<?php
/* @var $this StationparameterController */
/* @var $model StationParameter */
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
		<?php echo $form->label($model,'Time_interval_Rin'); ?>
		<?php echo $form->textField($model,'Time_interval_Rin'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Time_interval_U'); ?>
		<?php echo $form->textField($model,'Time_interval_U'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'U_abnormal_limit'); ?>
		<?php echo $form->textField($model,'U_abnormal_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'T_abnormal_limit'); ?>
		<?php echo $form->textField($model,'T_abnormal_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Rin_abnormal_limit'); ?>
		<?php echo $form->textField($model,'Rin_abnormal_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'T_upper_limit'); ?>
		<?php echo $form->textField($model,'T_upper_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'T_lower_limit'); ?>
		<?php echo $form->textField($model,'T_lower_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Humi_upper_limit'); ?>
		<?php echo $form->textField($model,'Humi_upper_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Humi_lower_limit'); ?>
		<?php echo $form->textField($model,'Humi_lower_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Group_I_criterion'); ?>
		<?php echo $form->textField($model,'Group_I_criterion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bytegeStatus_U_upper'); ?>
		<?php echo $form->textField($model,'bytegeStatus_U_upper'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bytegeStatus_U_lower'); ?>
		<?php echo $form->textField($model,'bytegeStatus_U_lower'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'FloatingbytegeStatus_U_upper'); ?>
		<?php echo $form->textField($model,'FloatingbytegeStatus_U_upper'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'FloatingbytegeStatus_U_lower'); ?>
		<?php echo $form->textField($model,'FloatingbytegeStatus_U_lower'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DisbytegeStatus_U_upper'); ?>
		<?php echo $form->textField($model,'DisbytegeStatus_U_upper'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DisbytegeStatus_U_lower'); ?>
		<?php echo $form->textField($model,'DisbytegeStatus_U_lower'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'N_Groups_Incide_Station'); ?>
		<?php echo $form->textField($model,'N_Groups_Incide_Station'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'HaveCurrentSensor'); ?>
		<?php echo $form->textField($model,'HaveCurrentSensor'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'StationCurrentSensorSpan'); ?>
		<?php echo $form->textField($model,'StationCurrentSensorSpan'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'StationCurrentSensorZeroADCode'); ?>
		<?php echo $form->textField($model,'StationCurrentSensorZeroADCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'OSC'); ?>
		<?php echo $form->textField($model,'OSC'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'DisbytegeCurrentLimit'); ?>
		<?php echo $form->textField($model,'DisbytegeCurrentLimit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bytegeCurrentLimit'); ?>
		<?php echo $form->textField($model,'bytegeCurrentLimit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TemperatureHighLimit'); ?>
		<?php echo $form->textField($model,'TemperatureHighLimit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TemperatureLowLimit'); ?>
		<?php echo $form->textField($model,'TemperatureLowLimit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'HumiH'); ?>
		<?php echo $form->textField($model,'HumiH'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'HumiL'); ?>
		<?php echo $form->textField($model,'HumiL'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TemperatureAdjust'); ?>
		<?php echo $form->textField($model,'TemperatureAdjust'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'HumiAdjust'); ?>
		<?php echo $form->textField($model,'HumiAdjust'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->