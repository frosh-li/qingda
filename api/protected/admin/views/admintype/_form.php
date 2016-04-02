<?php
/* @var $this AdmintypeController */
/* @var $model Admintype */
/* @var $form CActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'admintype-form',
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
<?php echo $form->textFieldRow($model, 'role',array('hint'=>'用户权限类型ID','hintOptions'=>array('class'=>'inline'))); ?>
<?php echo $form->textFieldRow($model, 'typename',array('hint'=>'后台用户类型','hintOptions'=>array('class'=>'inline'))); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
</div>

<?php $this->endWidget(); ?>