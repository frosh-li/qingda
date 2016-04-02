<?php
/* @var $this AdmintypeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Admintypes',
);

$this->menu=array(
	array('label'=>'Create Admintype', 'url'=>array('create')),
	array('label'=>'Manage Admintype', 'url'=>array('admin')),
);
?>

<h1>Admintypes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
