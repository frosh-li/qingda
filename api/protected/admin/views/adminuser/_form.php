<?php
/* @var $this AdminuserController */
/* @var $model Adminuser */
/* @var $form CActiveForm */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'adminuser-form',
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
<?php echo $form->textFieldRow($model, 'username', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'name', array('class'=>'input-large')); ?>
<?php echo $form->textFieldRow($model, 'department', array('class'=>'input-large')); ?>
<div class="control-group ">
    <?php echo $form->labelEx($model,'password',array('class'=>'control-label')); ?>
    <div class="controls">
        <input class="input-large" name="Adminuser[password]" id="Adminuser_password" type="text" maxlength="128" value="">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button','htmlOptions'=>array('id'=>'passwdgre'),'label'=>'生成密码')); ?>
    </div>
</div>
<?php echo $form->textFieldRow($model, 'password_repeat', array('class'=>'input-large')); ?>

<?php echo $form->dropDownListRow($model, 'role', CHtml::listData($model->getAllRoleFile(),'id','name')); ?>
<?php //echo $form->dropDownListRow($model, 'catalog', CHtml::listData($model->getAllCate(),'id','name')); ?>
<?php //echo $form->dropDownListRow($model, 'pid',array('empty'=>'请选择')); ?>
<?php //echo $form->dropDownListRow($model, 'cid', array('empty'=>'请选择')); ?>
<?php echo $form->textFieldRow($model, 'email', array('class'=>'input-large')); ?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? '创建' : '保存')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'label'=>'重置')); ?>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
function getRandomString(len) {
    len = len || 32;
    var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'; // 默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1
    var maxPos = chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
        pwd += chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}
$(document).ready(function(){
  $("#passwdgre").click(function(){
    var password = getRandomString(6);
    $("#Adminuser_password").val(password);
    $("#Adminuser_password_repeat").val(password);
  });
});
</script>
<?php
  Yii::app()->clientScript->registerScript('Adminuser_catalog',"
      var pid = $('#Adminuser_catalog').val();
      var Channels = {};
        Channels.pid = pid;
      $.ajax({
      url: '".CController::createUrl('channels/subcate')."',
      type: 'POST',
      data: Channels,
      success: function(html){
      $('#Adminuser_pid').html(html);
      $('#Adminuser_pid').val(".$model->pid.");
      var Channels = {};
          Channels.pid = $('#Adminuser_pid').val();;
      $.ajax({
        url: '".CController::createUrl('channels/subthree')."',
        type: 'POST',
        data: Channels,
        success: function(html){
        $('#Adminuser_cid').html(html);
        $('#Adminuser_cid').val(".$model->cid.");
        }
      });

      }
    });
  ");
?>
<script>
    $('#Adminuser_catalog').change(function  () {
        var pid = $('#Adminuser_catalog').val();
        if (pid ==0) {
            $('#Adminuser_pid').html('');
            $('#Adminuser_cid').html('');
            return;
        }
        var Channels = {};
        Channels.pid = pid;
        $.ajax({
          url: '<?php echo $this->createUrl("channels/subcate");?>',
          type: 'POST',
          data: Channels,
          success: function(html){
            $('#Adminuser_pid').html(html);
            $('#Adminuser_pid').val(<?php echo $model->pid;?>);
            var pid = $('#Adminuser_pid').val();
            var Channels = {};
            Channels.pid = pid;
            $.ajax({
              url: '<?php echo $this->createUrl("channels/subthree");?>',
              type: 'POST',
              data: Channels,
              success: function(html){
                $('#Adminuser_cid').html(html);
                $('#Adminuser_cid').val(<?php echo $model->cid;?>);
                
              }
            });

          }
        });
    });
    $('#Adminuser_pid').change(function  () {
        var pid = $('#Adminuser_pid').val();
        if (pid ==0) {
            $('#Adminuser_cid').html('');
            return;
        }
        var Channels = {};
        Channels.pid = pid;
        $.ajax({
          url: '<?php echo $this->createUrl("channels/subthree");?>',
          type: 'POST',
          data: Channels,
          success: function(html){
            $('#Adminuser_cid').html(html);
            $('#Adminuser_cid').val(<?php echo $model->cid;?>);
          }
        });
    })
</script>