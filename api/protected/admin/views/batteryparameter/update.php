<?php
/* @var $this BatteryparameterController */
/* @var $model BatteryParameter */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('���¹���'=>$this->createUrl('articles/admin'), '�޸ĵ��Ĭ�ϲ���'),
        'homeLink'=>CHtml::link('�˵�',array('site/welcome'),array('target'=>'main')),
        'htmlOptions'=>array('class'=>''),
        'separator'=>'/'));
?>
    <div class="iframaincontent">
    <h4>�޸ĵ��Ĭ�ϲ���</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
