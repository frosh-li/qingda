<?php
/* @var $this StationparameterController */
/* @var $model StationParameter */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('内容管理'=>'#','站默认参数管理'),
        'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
        'htmlOptions'=>array('class'=>''),
        'separator'=>'/'));
?>
<div class="iframaincontent">
    <h4>站默认参数管理</h4>


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
            array('name'=>'Time_interval_Rin', 'header'=>'内阻测量间隔'),
            array('name'=>'Time_interval_U', 'header'=>'电压测量间隔'),
            array('name'=>'U_abnormal_limit', 'header'=>'站电压异常限'),
            array('name'=>'T_abnormal_limit', 'header'=>'站温度异常限'),
            array('name'=>'Rin_abnormal_limit', 'header'=>'站内阻异常限'),
            array('name'=>'T_upper_limit', 'header'=>'站温度上限'),
            array('name'=>'T_lower_limit', 'header'=>'站温度下限'),
            array('name'=>'Humi_upper_limit', 'header'=>'站湿度上限'),
            array('name'=>'Humi_lower_limit', 'header'=>'站湿度下限'),
            array('name'=>'Group_I_criterion', 'header'=>'站电流状态判据'),
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
