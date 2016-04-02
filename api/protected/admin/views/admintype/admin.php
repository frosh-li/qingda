<?php
/* @var $this AdmintypeController */
/* @var $model Admintype */

 $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('用户类型管理'=>'#','后台用户类型管理'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>后台用户类型管理</h4>
<p>
    <a href="<?php echo $this->createUrl('admintype/create'); ?>">
        <button class="btn btn-success btn-small" type="button">添加后台用户类型</button>
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
	'columns'=>array(
		array('name'=>'id', 'header'=>'#','htmlOptions'=>array('style'=>'width: 50px')),
        array('name'=>'role', 'header'=>'权限'),
        array('name'=>'typename', 'header'=>'类型名称'),
		array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view}{update}',
            'htmlOptions'=>array('style'=>'width: 70px'),
        ),
	),
)); ?>
</div>