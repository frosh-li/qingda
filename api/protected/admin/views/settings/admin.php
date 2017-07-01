<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('网站设置'=>'#','全局设置'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h3>设置管理</h3>
<p>
    <a class="btn" href="<?php echo $this->createUrl('settings/create'); ?>">
        添加配置
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
        array('name'=>'categoryid', 'header'=>'分类'),
        array('name'=>'property', 'header'=>'键值'),
        array('name'=>'setvalue', 'header'=>'设置'),
        array('name'=>'name', 'header'=>'名称'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); 
?>
</div>
