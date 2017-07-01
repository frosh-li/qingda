<?php
/* @var $this SysuserController */
/* @var $model Sysuser */

$this->breadcrumbs=array(
	'Sysusers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Sysuser', 'url'=>array('index')),
	array('label'=>'Create Sysuser', 'url'=>array('create')),
	array('label'=>'Update Sysuser', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Sysuser', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Sysuser', 'url'=>array('admin')),
);
?>

<h1>View Sysuser #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'name',
		'gender',
		'password',
		'salt',
		'role',
		'phone',
		'email',
		'postname',
		'location',
		'site',
		'profile',
		'last_login_time',
		'create_time',
		'update_time',
	),
)); ?>
