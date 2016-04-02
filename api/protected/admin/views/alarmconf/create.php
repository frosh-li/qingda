<?php
/* @var $this AlarmconfController */
/* @var $model AlarmConf */
 $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('文章管理'=>$this->createUrl('articles/admin'), '添加警情分类'),
        'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
        'htmlOptions'=>array('class'=>''),
        'separator'=>'/'));
?>
    <div class="iframaincontent">
        <h4>添加警情分类</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
       </div>
