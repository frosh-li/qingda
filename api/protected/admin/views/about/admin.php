<?php
/* @var $this AboutController */
/* @var $model About */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('系统管理'=>'#','关于我们'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>关于我们</h4>
<p>
    <a class="btn" href="<?php echo $this->createUrl('about/create'); ?>">
        发布单页
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
        array('name'=>'id', 'header'=>'#','htmlOptions'=>array('style'=>'width: 50px')),
        array('name'=>'name', 'header'=>'名称'),
        array('name'=>'title', 'header'=>'标题'),
        array('name'=>'update_time', 'header'=>'发布时间','type'=>'datetime'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 90px'),
        ),
    ),
)); 
?>
</div>