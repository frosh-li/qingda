<?php
/* @var $this AlarmconfController */
/* @var $model AlarmConf */
/* @var $form CActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'alarm-conf-form',
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
<?php echo $form->dropDownListRow($model, 'category', $model->categorys); ?>
<?php echo $form->textFieldRow($model, 'type', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'content', array('class'=>'input-xxlarge')); ?>
<?php echo $form->textFieldRow($model, 'suggest', array('class'=>'input-xxlarge')); ?>
<?php echo $form->radioButtonListInlineRow($model, 'send_email', array(1=>'是',0=>'否')); ?>
<?php echo $form->radioButtonListInlineRow($model, 'send_msg', array(1=>'是',0=>'否')); ?>
<?php echo $form->radioButtonListInlineRow($model, 'judge_type', array(1=>'百分比',0=>'绝对值')); ?>
<?php echo $form->radioButtonListInlineRow($model, 'can_ignore', array(1=>'可忽略',2=>'不可忽略')); ?>
<?php echo $form->dropDownListRow($model, 'operator', $model->operators); ?>
<?php echo $form->textFieldRow($model, 'type_value', array('class'=>'input-large')); ?>
<?php echo $form->dropDownListRow($model, 'alarm_type', $model->alarmaypes); ?>
<?php echo $form->textFieldRow($model, 'alarm_code', array('class'=>'input-large')); ?>


    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
    </div>

<?php $this->endWidget(); ?>