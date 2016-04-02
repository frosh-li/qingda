<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'articles-form',
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

<?php echo $form->dropDownListRow($model, 'pid', CHtml::listData($channel,'id','title')); ?>
<?php echo $form->dropDownListRow($model, 'cid', array('empty'=>'请选择')); ?>
<?php echo $form->dropDownListRow($model, 'scid', array('empty'=>'请选择')); ?>
<?php echo $form->radioButtonListInlineRow($model, 'type', array(1=>'是',0=>'否')); ?>
<?php echo $form->textFieldRow($model, 'title', array('class'=>'input-xxlarge')); ?>
<?php //echo $form->textFieldRow($model, 'seotitle', array('class'=>'input-xxlarge')); ?>
<?php //echo $form->textFieldRow($model, 'tag', array('class'=>'input-xxlarge')); ?>
<?php //echo $form->textFieldRow($model, 'metakeywords', array('class'=>'input-xxlarge')); ?>
<?php //echo $form->textAreaRow($model, 'metadesc', array('class'=>'span7', 'rows'=>2)); ?>
<?php echo $form->textAreaRow($model, 'content', array('class'=>'input-xxlarge','rows'=>2)); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
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
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
	// 'id'=>'Articles_content',	//Textarea id
	'model'=>'articles',
	'name'=>'content',
	// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
	'items' => array(
		'width'=>'100%',
		'height'=>'300px',
		'themeType'=>'simple',
		'allowImageUpload'=>true,
		'allowFileManager'=>false,
		'uploadJson'=>Yii::app()->createUrl('/upload'),
		'items'=>array('undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
        'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
        'anchor', 'link', 'unlink','|','source',  ),
	),
)); ?>
<?php
	Yii::app()->clientScript->registerScript('Articles_cid',"
	    var pid = $('#Articles_pid').val();
	    var Channels = {};
        Channels.pid = pid;
        debugger;
	    $.ajax({
		  url: '".CController::createUrl('channels/subcate')."',
		  type: 'POST',
		  data: Channels,
		  success: function(html){
			$('#Articles_cid').html(html);
			$('#Articles_cid').val(".$model->cid.");
			var Channels = {};
        	Channels.pid = $('#Articles_cid').val();;
			$.ajax({
			  url: '".CController::createUrl('channels/subthree')."',
			  type: 'POST',
			  data: Channels,
			  success: function(html){
				$('#Articles_scid').html(html);
				$('#Articles_scid').val(".$model->scid.");
			  }
			});

		  }
		});
	");
?>
<script>
	$('#Articles_pid').change(function  () {
		var pid = $('#Articles_pid').val();
		if (pid ==0) {
			$('#Articles_cid').html('');
			$('#Articles_scid').html('');
			return;
		}
		var Channels = {};
        Channels.pid = pid;
	    $.ajax({
		  url: '<?php echo $this->createUrl("channels/subcate");?>',
		  type: 'POST',
		  data: Channels,
		  success: function(html){
			$('#Articles_cid').html(html);
			$('#Articles_cid').val(<?php echo $model->cid;?>);
			var cid = $('#Articles_cid').val();
            var Channels = {};
            Channels.pid = cid;
			$.ajax({
			  url: '<?php echo $this->createUrl("channels/subthree");?>',
			  type: 'POST',
			  data: Channels,
			  success: function(html){
				$('#Articles_scid').html(html);
				$('#Articles_scid').val(<?php echo $model->scid;?>);
			  }
			});
		  }
		});
	})
	$('#Articles_cid').change(function  () {
		var cid = $('#Articles_cid').val();
		if (cid ==0) {
			$('#Articles_scid').html('');
			return;
		}
		var Channels = {};
        Channels.pid = cid;
	    $.ajax({
		  url: '<?php echo $this->createUrl("channels/subthree");?>',
		  type: 'POST',
		  data: Channels,
		  success: function(html){
			$('#Articles_scid').html(html);
			$('#Articles_scid').val(<?php echo $model->scid;?>);
		  }
		});
	})
</script>