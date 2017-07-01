<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('焦点图管理'),
          'homeLink'=>CHtml::link('菜单',Yii::app()->homeUrl),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>焦点图管理</h4>
<p>
    <a class="btn" href="<?php echo $this->createUrl('slideshow/create'); ?>">
        添加广告焦点图
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
        array('name'=>'title', 'header'=>'链接标题'),
        array('name'=>'url', 'header'=>'链接URL'),
        array('name'=>'token', 'header'=>'显示位置','value' => '$data->getTypeText()','type' => 'raw'),
        array('name'=>'created', 'header'=>'发布时间','type'=>'datetime'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 90px'),
        ),
    ),
)); 
?>
</div>
