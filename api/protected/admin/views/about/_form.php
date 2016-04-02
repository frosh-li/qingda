<?php
/* @var $this AboutController */
/* @var $model About */
/* @var $form CActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'about-form',
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
<?php echo $form->textFieldRow($model, 'title', array('class'=>'input-xxlarge')); ?>
 <div class="control-group">
	<?php echo $form->labelEx($model,'content',array('class'=>'control-label')); ?>
	<div class="controls">
	<?php
		$this->widget('ext.dxheditor.DxhEditor',array(
			'model'=>$model,
			'attribute' => 'content',
			'htmlOptions' => array('style'=>'width:100%;height:350px;'),
            'options' =>array('tools'=>'Blocktag,FontSize,FontColor,BackColor,Align,List,Table,Cut,Copy,Paste,Pastetext,Img,Media,Emot,Link,Unlink,Removeformat,Source'),
	));?>
	</div>
	<?php echo $form->error($model,'content'); ?>
</div>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->