<?php 
$this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('我的文章'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>我的文章</h4>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',//default striped bordered condensed
    'dataProvider'=>$model->my(),
    'template'=>"{items}{pager}",
    'ajaxUpdate'=>false,
    'filter'=>$model,
    //'afterAjaxUpdate'=>'changeiframe',
    'pagerCssClass'=>'pagination pagination-right',
    //'pager' => array('class'=>'CombPager'),
    'columns'=>array(
        array('name'=>'id', 'header'=>'#','htmlOptions'=>array('style'=>'width: 50px')),
        // array('name'=>'pid', 'header'=>'主栏目','value' =>'$data->getPidText($data->pid)','type' => 'raw'),
        // array('name'=>'pid', 'header'=>'二级栏目','value' =>'$data->getPidText($data->cid)','type' => 'raw'),
        array('name'=>'title', 'header'=>'栏目标题','value' =>'$data->getTitle()','type' => 'raw'),
        // array('name'=>'ishidden', 'header'=>'是否隐藏','type'=>'boolean'),
        // array('name'=>'ordernum', 'header'=>'排序'),
        // array('name'=>'link', 'header'=>'链接URL'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}',
            'buttons' => array(
                'update' => array(
                    'label' =>'编辑文章内容',
                    'url'=>'Yii::app()->createUrl("channels/oupdate", array("id"=>$data->id))',)
            ),
            'htmlOptions'=>array('style'=>'width: 120px'),
        ),
    ),
)); 
?>
</div>