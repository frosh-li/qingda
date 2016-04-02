<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('内容管理'=>'#','文章管理'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>所有公开信息</h4>
<p>
    <a class="btn" href="<?php echo $this->createUrl('articles/create'); ?>">
        添加文章
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
        array('name'=>'title', 'header'=>'标题'),
        array('name'=>'pid', 'header'=>'目录','value'=>'$data->getPidName()'),

        array('name'=>'create_time', 'header'=>'发布时间','type'=>'datetime'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 100px'),
        ),
    ),
)); 
?>
</div>