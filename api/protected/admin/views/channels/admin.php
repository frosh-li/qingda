<?php 
$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('内容管理'=>'#','栏目管理'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>栏目管理</h4>
<p>
    <a class="btn" href="<?php echo $this->createUrl('channels/create'); ?>">
        添加栏目
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
        // array('name'=>'id', 'header'=>'#','htmlOptions'=>array('style'=>'width: 50px')),
        array('name'=>'pid', 'header'=>'主栏目','value' =>'$data->getPidText($data->pid)','type' => 'raw'),
        array('name'=>'pid', 'header'=>'二级栏目','value' =>'$data->getPidText($data->cid)','type' => 'raw'),
        array('name'=>'title', 'header'=>'栏目名称'),
        // array('name'=>'ishidden', 'header'=>'是否隐藏','type'=>'boolean'),
        // array('name'=>'ordernum', 'header'=>'排序'),
        // array('name'=>'link', 'header'=>'链接URL'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{add}{view}{update}{delete}',
            'buttons' => array(
                'delete' => array('visible' =>'$data->systemtype == 0'),
                'add' => array('label' =>'添加子分类',
                                'icon'=>'plus',
                                'options'=>array('style'=>'padding:0 5px;'),
                                'url'=>'Yii::app()->createUrl("channels/create", array("id"=>$data->id))',
                                'visible' =>'$data->pid == 0'
                ),
            ),
            'htmlOptions'=>array('style'=>'width: 120px'),
        ),
    ),
)); 
?>
</div>