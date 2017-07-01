<?php
/* @var $this LinksController */
/* @var $model Links */

$this->pageTitle=Yii::app()->name;
?>
<?php $this->widget('bootstrap.widgets.TbBreadcrumbs',
	array('links'=>array('友情链接管理'=>$this->createUrl('links/admin'), '添加友情链接'),
		  'homeLink'=>CHtml::link('菜单',array('site/welcome'),array('target'=>'main')),
		  'htmlOptions'=>array('class'=>''),
		  'separator'=>'/'));
?>
<div class="iframaincontent">
<h4>添加友情链接</h4>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>