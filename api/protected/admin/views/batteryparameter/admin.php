<?php
/* @var $this BatteryparameterController */
/* @var $model BatteryParameter */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('内容管理'=>'#','电池默认参数管理'),
        'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
        'htmlOptions'=>array('class'=>''),
        'separator'=>'/'));
?>
<div class="iframaincontent">
    <h4>电池默认参数管理</h4>

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
            array('name'=>'CurrentAdjust_KV', 'header'=>'电压测量修正'),
            array('name'=>'TemperatureAdjust_KT', 'header'=>'温度测量修正'),
            array('name'=>'T0_ADC', 'header'=>'T0校准点ADC'),
            array('name'=>'T0_Temperature', 'header'=>'T0校准点温度'),
            array('name'=>'T1_ADC', 'header'=>'T1校准点ADC'),
            array('name'=>'T1_Temperature', 'header'=>'T1校准点温度'),
            array('name'=>'Rin_Span', 'header'=>'内阻测量量程'),
            array('name'=>'OSC', 'header'=>'OSC'),
            array('name'=>'BatteryU_H', 'header'=>'电池电压高压限'),
            array('name'=>'BaterryU_L', 'header'=>'电池电压低压限'),
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