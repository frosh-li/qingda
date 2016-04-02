<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
  array('links'=>array('欢迎登录信息公开管理系统'),
      'homeLink'=>CHtml::link('菜单',Yii::app()->homeUrl),
      'htmlOptions'=>array('class'=>''),
      'separator'=>'/'));
?>
<div class="pd20 left">
  <div class="alert alert-welcome">
    <div class="i-title">您好！欢迎登录<?php echo Yii::app()->name;?>管理系统。</div>
    <div class="i-info">上次登录时间：<?php echo Yii::app()->user->lastLoginTime;?></div>
  </div>

</div>