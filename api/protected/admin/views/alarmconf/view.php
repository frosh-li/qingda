<?php
/* @var $this AlarmconfController */
/* @var $model AlarmConf */

$this->breadcrumbs=array(
	'Alarm Confs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AlarmConf', 'url'=>array('index')),
	array('label'=>'Create AlarmConf', 'url'=>array('create')),
	array('label'=>'Update AlarmConf', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AlarmConf', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AlarmConf', 'url'=>array('admin')),
);
?>

<h1>View AlarmConf #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'content',
		'suggest',
		'send_email',
		'send_msg',
		'type_red',
		'type_orange',
		'type_yellow',
		'create_time',
		'update_time',
	),
)); ?>
