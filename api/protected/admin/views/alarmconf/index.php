<?php
/* @var $this AlarmconfController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Alarm Confs',
);

$this->menu=array(
	array('label'=>'Create AlarmConf', 'url'=>array('create')),
	array('label'=>'Manage AlarmConf', 'url'=>array('admin')),
);
?>

<h1>Alarm Confs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
