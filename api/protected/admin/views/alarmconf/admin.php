<?php
/* @var $this AlarmconfController */
/* @var $model AlarmConf */
$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('内容管理'=>'#','警情分类管理'),
        'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
        'htmlOptions'=>array('class'=>''),
        'separator'=>'/'));
?>
<div class="iframaincontent">
    <h4>警情分类管理</h4>
    <p>
        <a class="btn" href="<?php echo $this->createUrl('alarmconf/create'); ?>">
            添加默认警情分类
        </a>
    </p>

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
            array('name'=>'category', 'header'=>'分类','htmlOptions'=>array('style'=>'width: 70px'),'filter'=>$model->categorys,'value' =>'$data->getCategory()','type' => 'raw'),
            array('name'=>'type', 'header'=>'编号','htmlOptions'=>array('style'=>'width: 50px')),
            array('name'=>'content', 'header'=>'警情'),
            array('name'=>'suggest', 'header'=>'处理建议'),
            array('name'=>'send_email', 'header'=>'发邮件','htmlOptions'=>array('style'=>'width: 50px'),'filter'=>array('1'=>'是','0'=>'否'),
                'value'=>'($data->send_email=="1")?("是"):("否")'),
            array('name'=>'send_msg', 'header'=>'发短信','htmlOptions'=>array('style'=>'width: 50px'),'filter'=>array('1'=>'是','0'=>'否'),
                'value'=>'($data->send_msg=="1")?("是"):("否")'),
            array('name'=>'operator', 'header'=>'判断操作','htmlOptions'=>array('style'=>'width: 80px'),'filter'=>$model->operators,'value' =>'$data->getOperatorText()',),
            array('name'=>'type_value', 'header'=>'判断值','htmlOptions'=>array('style'=>'width: 60px')),
            array('name'=>'alarm_type', 'header'=>'警情类型','htmlOptions'=>array('style'=>'width: 60px'),'filter'=>$model->alarmaypes,'value' =>'$data->getAlarmTypeText()','type' => 'raw'),

            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'htmlOptions'=>array('style'=>'width: 100px'),
            ),
        ),
    ));
    ?>
</div>