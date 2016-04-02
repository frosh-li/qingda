<?php 
$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('幻灯片管理'=>$this->createUrl('slideshow/admin'),'更新幻灯片'),
          'homeLink'=>CHtml::link('菜单',Yii::app()->homeUrl),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>更新幻灯片：<?php echo $model->id; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>