<?php
/* @var $this GroupparameterController */
/* @var $model GroupParameter */
/* @var $form CActiveForm */

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'group-parameter-form',
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

<?php echo $form->textFieldRow($model, 'HaveCurrentSensor', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'StationCurrentSensorSpan', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'StationCurrentSensorZeroADCode', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'OSC', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'DisbytegeCurrentLimit', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'bytegeCurrentLimit', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'TemperatureHighLimit', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'TemperatureLowLimit', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'HumiH', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'HumiL', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'TemperatureAdjust', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'HumiAdjust', array('class'=>'input-large')); ?>


    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
    </div>

<?php $this->endWidget(); ?>