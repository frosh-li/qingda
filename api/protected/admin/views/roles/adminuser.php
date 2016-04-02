<?php
/* @var $this AdminuserController */
/* @var $model Adminuser */

 $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('后台用户权限管理'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>后台用户权限管理</h4>
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
        array('name'=>'username', 'header'=>'登录名'),
        array('name'=>'name', 'header'=>'姓名'),
        array('name'=>'department', 'header'=>'部门'),
        array('name'=>'role', 'header'=>'角色','value' =>'$data->getRoleFileText()','filter'=>$model->getRoleFileFilter(),'type' => 'raw'),
        // array('name'=>'email', 'header'=>'邮箱'),
        //array('name'=>'password', 'header'=>'密码','htmlOptions'=>array('style'=>'width: 80px')),
        // array('name'=>'create_time', 'header'=>'创建时间'),
        // array('name'=>'last_login_time', 'header'=>'最后登录时间'),
        
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{view}{add}',
            'buttons' => array(
                'view' => array('label' =>'查看用户权限',
                                'url'=>'Yii::app()->createUrl("roles/view", array("uid"=>$data->id))'
                ),
                'add' => array('label' =>'配置用户权限',
                                'icon'=>'key',
                                'options'=>array('style'=>'padding:0 5px;'),
                                'url'=>'Yii::app()->createUrl("roles/create", array("uid"=>$data->id))',
                                // 'visible' =>'$data->pid == 0'
                ),
            ),
            'htmlOptions'=>array('style'=>'width: 90px'),
        ),
    ),
)); 
?>
</div>