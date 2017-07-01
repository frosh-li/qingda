<?php
/* @var $this AdminuserController */
/* @var $model Adminuser */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
array('links'=>array('用户管理'=>'#','修改后台用户'),
      'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
      'htmlOptions'=>array('class'=>''),
      'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>修改后台用户<?php echo $model->username; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>