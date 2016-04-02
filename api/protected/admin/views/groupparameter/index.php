<?php
/* @var $this GroupparameterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Group Parameters',
);

$this->menu=array(
	array('label'=>'Create GroupParameter', 'url'=>array('create')),
	array('label'=>'Manage GroupParameter', 'url'=>array('admin')),
);
?>

<h1>Group Parameters</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
