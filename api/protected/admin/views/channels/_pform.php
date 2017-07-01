<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'channels-form',
    'type'=>'horizontal',
    'htmlOptions'=>array('class'=>'well'),
    'enableAjaxValidation'=>false,
)); 
?>
<p class="note">带有<span class="required">*</span> 的项目为必填项</p>
<?php echo $form->errorSummary($model); ?>

<?php echo $form->dropDownListRow($model, 'channeltype', $model->getTypeOptions()); ?>
<?php echo $form->textFieldRow($model, 'title', array('class'=>'input-xlarge')); ?>
<?php echo $form->textFieldRow($model, 'ordernum',array('hint'=>'数越大越靠前','hintOptions'=>array('class'=>'inline'))); ?>
<?php echo $form->textFieldRow($model, 'link', array('class'=>'input-xlarge','hint'=>'如果是一个连接，就填写','hintOptions'=>array('class'=>'help-inline'))); ?>
<?php echo $form->radioButtonListInlineRow($model, 'ishidden', array(1=>'是',0=>'否')); ?>
<?php echo $form->checkBoxListInlineRow($model, 'positions', array(1=>'导航栏',2=>'底部')); ?>
<?php echo $form->radioButtonListInlineRow($model, 'target', array(1=>'是',0=>'否')); ?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
</div>
<?php $this->endWidget(); ?>