<?php
/* @var $this AdminuserController */
/* @var $model Adminuser */

 $this->widget('bootstrap.widgets.TbBreadcrumbs',
    array('links'=>array('用户管理'=>'#','后台用户管理'),
          'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
          'htmlOptions'=>array('class'=>''),
          'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>用户<?php echo $user['username'];?>权限查看</h4>
<p>
    <a class="btn" href="<?php echo $this->createUrl('roles/create',array('uid'=>$user['id'])); ?>">
        添加用户权限
    </a>
</p>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',//default striped bordered condensed
    'dataProvider'=>$model->search($user['id']),
    'template'=>"{items}{pager}",
    'ajaxUpdate'=>false,
    'filter'=>$model,
    //'afterAjaxUpdate'=>'changeiframe',
    'pagerCssClass'=>'pagination pagination-right',
    //'pager' => array('class'=>'CombPager'),
    'columns'=>array(
        // array('name'=>'id', 'header'=>'#','htmlOptions'=>array('style'=>'width: 50px')),
        array('name'=>'username', 'header'=>'登录名','htmlOptions'=>array('style'=>'width: 60px')),
        array('name'=>'pid', 'header'=>'一级栏目','value' =>'$data->getPidText()','type' => 'raw'),
        array('name'=>'cid', 'header'=>'二级栏目','value' =>'$data->getCidText()','type' => 'raw'),
        array('name'=>'channel', 'header'=>'三级栏目','value' =>'$data->getChannelText()','type' => 'raw'),
        // array('name'=>'email', 'header'=>'邮箱'),
        //array('name'=>'password', 'header'=>'密码','htmlOptions'=>array('style'=>'width: 80px')),
        // array('name'=>'create_time', 'header'=>'创建时间'),
        // array('name'=>'last_login_time', 'header'=>'最后登录时间'),
        
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{delete}',
            'buttons' => array(
                'delete' => array('label' =>'删除该用户权限',
                                // 'url'=>'Yii::app()->createUrl("roles/list", array("id"=>$data->id))'
                ),
            ),
            'htmlOptions'=>array('style'=>'width: 90px'),
        ),
    ),
)); 
?>
</div>