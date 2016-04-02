<?php
/* @var $this LinksController */
/* @var $model Links */

$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('内容管理'=>'#','公开信息管理'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>公开信息管理</h4>
<p>
    <a class="btn" href="<?php echo $this->createUrl('links/create'); ?>">
        添加公开信息
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
        array('name'=>'name', 'header'=>'网站名称'),
        array('name'=>'url', 'header'=>'网站URL'),
        array('name'=>'update_time', 'header'=>'更新时间','type'=>'datetime'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 90px'),
        ),
    ),
)); 
?>
</div>