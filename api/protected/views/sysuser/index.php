<?php
/* @var $this SysuserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Sysusers',
);

$this->menu=array(
	array('label'=>'Create Sysuser', 'url'=>array('create')),
	array('label'=>'Manage Sysuser', 'url'=>array('admin')),
);
?>

<h1>Sysusers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
