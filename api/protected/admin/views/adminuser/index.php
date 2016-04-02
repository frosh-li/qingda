<?php
/* @var $this AdminuserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Adminusers',
);

$this->menu=array(
	array('label'=>'Create Adminuser', 'url'=>array('create')),
	array('label'=>'Manage Adminuser', 'url'=>array('admin')),
);
?>

<h1>Adminusers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
