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

<?php echo $form->dropDownListRow($model, 'pid',$model->getPidOptions()); ?>
<?php echo $form->dropDownListRow($model, 'channeltype', $model->getTypeOptions()); ?>
<?php echo $form->textFieldRow($model, 'title', array('class'=>'input-xlarge')); ?>
<?php echo $form->textFieldRow($model, 'ordernum',array('hint'=>'数越大越靠前','hintOptions'=>array('class'=>'inline'))); ?>
<?php echo $form->textFieldRow($model, 'link', array('class'=>'input-xlarge','hint'=>'如果是一个连接，就填写','hintOptions'=>array('class'=>'help-inline'))); ?>
<?php echo $form->radioButtonListInlineRow($model, 'ishidden', array(1=>'是',0=>'否')); ?>
<?php echo $form->checkBoxListInlineRow($model, 'positions', array(1=>'导航栏',2=>'底部')); ?>
<?php echo $form->radioButtonListInlineRow($model, 'target', array(1=>'是',0=>'否')); ?>
<?php //echo $form->textFieldRow($model, 'seotitle', array('class'=>'input-xxlarge')); ?>
 <?php //echo $form->textAreaRow($model, 'content', array('class'=>'input-xxlarge','rows'=>2)); ?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
</div>
<?php 
// $this->widget('ext.kindeditor.KindEditorWidget',array(
// 	// 'id'=>'Articles_content',	//Textarea id
// 	'model'=>'channels',
// 	'name'=>'content',
// 	// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
// 	'items' => array(
// 		'width'=>'100%',
// 		'height'=>'300px',
// 		'themeType'=>'simple',
// 		'allowImageUpload'=>true,
// 		'allowFileManager'=>false,
// 		'uploadJson'=>Yii::app()->createUrl('/upload'),
// 		'items'=>array('undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
//         'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
//         'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
//         'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen',
//         'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
//         'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
//         'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
//         'anchor', 'link', 'unlink','|','source',  ),
// 	),
// )); 
?>
<?php $this->endWidget(); ?>
