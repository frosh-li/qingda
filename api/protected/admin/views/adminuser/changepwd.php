<?php
/* @var $this AdminuserController */
/* @var $model Adminuser */

 $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('修改密码'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>

<div class="iframaincontent">
<h4>修改密码</h4>

<?php echo $this->renderPartial('_change', array('model'=>$model)); ?>
</div>