<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array($channel),
          'homeLink'=>CHtml::link('首页',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4><?php echo $channel;?></h4>
<p>
    <a class="btn" href="<?php echo $this->createUrl('home/create',array('cid'=>$id)); ?>">
        添加文章
    </a>
</p>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',//default striped bordered condensed
    'dataProvider'=>$model->home($id),
    'template'=>"{items}{pager}",
    'ajaxUpdate'=>false,
    'filter'=>$model,
    //'afterAjaxUpdate'=>'changeiframe',
    'pagerCssClass'=>'pagination pagination-right',
    //'pager' => array('class'=>'CombPager'),
    'columns'=>array(
        // array('name'=>'id', 'header'=>'#','htmlOptions'=>array('style'=>'width: 50px')),
        array('name'=>'title', 'header'=>'标题'),
        array('name'=>'type', 'header'=>'置顶','filter'=>array('1'=>'是','0'=>'否'),
            'value'=>'($data->type=="1")?("是"):("否")'),
        array('name'=>'create_time', 'header'=>'发布时间','type'=>'datetime'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 80px'),
        ),
    ),
)); 
?>
</div>