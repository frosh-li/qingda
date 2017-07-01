<?php
/* @var $this GroupparameterController */
/* @var $model GroupParameter */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('文章管理'=>$this->createUrl('articles/admin'), '添加组默认参数'),
        'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
        'htmlOptions'=>array('class'=>''),
        'separator'=>'/'));
?>
    <div class="iframaincontent">
        <h4>添加组默认参数</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
