<?php
/* @var $this GroupparameterController */
/* @var $model GroupParameter */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('内容管理'=>'#','组默认参数管理'),
        'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
        'htmlOptions'=>array('class'=>''),
        'separator'=>'/'));
?>
<div class="iframaincontent">
    <h4>组默认参数管理</h4>

    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type'=>'striped bordered condensed',//default striped bordered condensed
        'dataProvider'=>$model->search(),
        'template'=>"{items}{pager}",
        'ajaxUpdate'=>false,
        'filter'=>$model,
        //'afterAjaxUpdate'=>'changeiframe',
        'pagerCssClass'=>'pagination pagination-right',
        //'pager' => array('class'=>'CombPager'),
        'columns'=>array(
            //array('name'=>'id', 'header'=>'#','htmlOptions'=>array('style'=>'width: 50px')),
            array('name'=>'HaveCurrentSensor','header'=>'有无电流传感器'),
            array('name'=>'StationCurrentSensorSpan','header'=>'传感器量程'),
            array('name'=>'StationCurrentSensorZeroADCode', 'header'=>'零位AD码'),
            array('name'=>'OSC', 'header'=>'OSC'),
            array('name'=>'DisbytegeCurrentLimit', 'header'=>'放电电流限'),
            array('name'=>'bytegeCurrentLimit', 'header'=>'充电电流限'),
            array('name'=>'TemperatureHighLimit', 'header'=>'温度上限'),
            array('name'=>'TemperatureLowLimit','header'=>'温度下限'),
            array('name'=>'HumiH', 'header'=>'湿度上限'),
            array('name'=>'HumiL', 'header'=>'湿度下限'),
            //array('name'=>'Electrode_T_H_Limit', 'header'=>'电极温度高温限'),
            //array('name'=>'Electrode_T_L_Limit', 'header'=>'电极温度低温限'),

            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{update}',
                'htmlOptions'=>array('style'=>'width: 30px'),
            ),
        ),
    ));
    ?>

</div>