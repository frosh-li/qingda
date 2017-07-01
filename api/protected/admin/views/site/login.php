<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'login-form',
    //'type'=>'vertical',
    'enableClientValidation'=>true,
  	'clientOptions'=>array(
  		'validateOnSubmit'=>true,
  	)
));
?>
<div class="modal" id="modal-login">
  <div class="modal-header">
    <h3><?php echo Yii::app()->name;?>后台管理系统</h3>
  </div>
  <div class="modal-body">
  <?php echo $form->errorSummary($model); ?>
  <fieldset>
      <?php echo $form->textFieldRow($model, 'username', array('class'=>'input-larger', 'prepend'=>'<i class="icon-user	"></i>')); ?>
      <?php echo $form->passwordFieldRow($model, 'password', array('class'=>'input-larger', 'prepend'=>'<i class="icon-lock"></i>')); ?>
      <?php echo $form->checkBoxRow($model, 'rememberMe', array()); ?>
      <?php //$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'登录')); ?>
  <input class="btn btn-primary" type="submit" value="登录" name="yt0"></input>
  </fieldset>
  </div>
  <div class="modal-footer">
    <a href="<?php echo Yii::app()->request->hostInfo;?>" class="btn btn-primary">返回首页</a>
  </div>
</div>
<div class="modal-backdrop"></div>
<?php $this->endWidget(); ?>