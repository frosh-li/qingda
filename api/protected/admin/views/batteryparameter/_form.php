<?php
/* @var $this BatteryparameterController */
/* @var $model BatteryParameter */
/* @var $form CActiveForm */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'battery-parameter-form',
    'type'=>'horizontal',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data','class'=>'well'),
    ));
    ?>
    <?php
    $this->widget('bootstrap.widgets.TbAlert', array(
        'id'=>'alert',
        'block'=>false,
        'fade'=>true,
        'closeText'=>true,
        'alerts'=>array(
            'success'=>array('fade'=>true, 'closeText'=>'&times;'),
            'error'=>array('fade'=>true, 'closeText'=>'&times;'),
        ),
    ));
    ?>
    <p class="note">带有<span class="required">*</span> 的项目为必填项</p>

    <?php echo $form->errorSummary($model); ?>
<?php echo $form->textFieldRow($model, 'CurrentAdjust_KV', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'TemperatureAdjust_KT', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'T0_ADC', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'T0_Temperature', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'T1_ADC', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'T1_Temperature', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'Rin_Span', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'OSC', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'BatteryU_H', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'BaterryU_L', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'Electrode_T_H_Limit', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'Electrode_T_L_Limit', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'Rin_High_Limit', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'Rin_Adjust_KR', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'PreAmp_KA', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'Rin_ExciteI_KI', array('class'=>'input-large')); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
    </div>

<?php $this->endWidget(); ?>