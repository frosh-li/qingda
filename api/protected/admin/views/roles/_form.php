<?php
/* @var $this RolesController */
/* @var $model Roles */
/* @var $form CActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'roles-form',
    'type'=>'horizontal',
    'htmlOptions'=>array('class'=>'well'),
    'enableAjaxValidation'=>false,
)); 
?>
<p class="note">带有<span class="required">*</span> 的项目为必填项</p>
<?php echo $form->errorSummary($model); ?>
<?php echo $form->hiddenField($model, 'uid', array('class'=>'input-large','readonly'=>true)); ?>
<?php echo $form->textFieldRow($model, 'username', array('class'=>'input-large','readonly'=>true)); ?>
<?php echo $form->dropDownListRow($model, 'pid', CHtml::listData($channel,'id','title')); ?>
<?php echo $form->dropDownListRow($model, 'cid', array('empty'=>'请选择')); ?>
<?php echo $form->dropDownListRow($model, 'channel', array('empty'=>'请选择')); ?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
</div>
<?php $this->endWidget(); ?>

<?php
	Yii::app()->clientScript->registerScript('Roles_pid',"
	    var pid = $('#Roles_pid').val();
	    var Channels = {};
        Channels.pid = pid;
	    $.ajax({
		  url: '".CController::createUrl('channels/subcate')."',
		  type: 'POST',
		  data: Channels,
		  success: function(html){
			$('#Roles_cid').html(html);
			$('#Roles_cid').val(".$model->cid.");
			var Channels = {};
        	Channels.pid = $('#Roles_cid').val();;
			$.ajax({
			  url: '".CController::createUrl('channels/subthree')."',
			  type: 'POST',
			  data: Channels,
			  success: function(html){
				$('#Roles_channel').html(html);
				$('#Roles_channel').val(".$model->channel.");
			  }
			});

		  }
		});
	");
?>
<script>
	$('#Roles_pid').change(function  () {
		var pid = $('#Roles_pid').val();
		if (pid ==0) {
			$('#Roles_cid').html('');
			$('#Roles_channel').html('');
			return;
		}
		var Channels = {};
        Channels.pid = pid;
	    $.ajax({
		  url: '<?php echo $this->createUrl("channels/subcate");?>',
		  type: 'POST',
		  data: Channels,
		  success: function(html){
			$('#Roles_cid').html(html);
			$('#Roles_cid').val(<?php echo $model->cid;?>);
			var cid = $('#Roles_cid').val();
            var Channels = {};
            Channels.pid = cid;
			$.ajax({
			  url: '<?php echo $this->createUrl("channels/subthree");?>',
			  type: 'POST',
			  data: Channels,
			  success: function(html){
				$('#Roles_channel').html(html);
				$('#Roles_channel').val(<?php echo $model->channel;?>);
			  }
			});
		  }
		});
	})
	$('#Roles_cid').change(function  () {
		var cid = $('#Roles_cid').val();
		if (cid ==0) {
			$('#Roles_channel').html('');
			return;
		}
		var Channels = {};
        Channels.pid = cid;
	    $.ajax({
		  url: '<?php echo $this->createUrl("channels/subthree");?>',
		  type: 'POST',
		  data: Channels,
		  success: function(html){
			$('#Roles_channel').html(html);
			$('#Roles_channel').val(<?php echo $model->channel;?>);
		  }
		});
	})
</script>