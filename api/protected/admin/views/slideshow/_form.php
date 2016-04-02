<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'slideshow-form',
    'type'=>'horizontal',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data','class'=>'well'),
)); 
?>
<fieldset>
<p class="note">带有<span class="required">*</span> 的项目为必填项</p>

	<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'title', array('class'=>'input-xxlarge')); ?>
<?php echo $form->textFieldRow($model, 'url', array('class'=>'input-xxlarge')); ?>
<?php echo $form->fileFieldRow($model, 'image'); ?>
<?php if($model->image){ 
  $imageurl = $model->image;
  if (strpos($model->image,'http') === false) {
    $imageurl = Yii::app()->baseUrl . $model->image;
  }
?>
<div class="control-group ">
	<div class="controls">
		<img src="<?php echo $imageurl ?>"  onload="DrawImage(this,400,400);"/>
	</div>
</div>
<?php } ?>
<?php echo $form->dropDownListRow($model, 'token', $model->getTypeOptions()); ?>
<?php echo $form->textFieldRow($model, 'sortnum', array('class'=>'input-xxlarge')); ?>

</fieldset>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? 'Create' : 'Save')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'Reset')); ?>
</div>
<?php $this->endWidget(); ?>
<script>
    function DrawImage(ImgD, FitWidth, FitHeight) { 
      var image = new Image(); 
      image.src = ImgD.src; 
      if (image.width > 0 && image.height > 0) { 
        if (image.width / image.height >= FitWidth / FitHeight) { 
          ImgD.height = FitHeight; 
          ImgD.width = (image.width * FitHeight) / image.height; 
        } else {
          ImgD.width = FitWidth; 
          ImgD.height = (image.height * FitWidth) / image.width;
        } 
      }
      $(ImgD).show();
    }
</script>