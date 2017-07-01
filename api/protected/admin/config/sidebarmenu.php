<?php
return array(
	array(
		'name' => '控制面板',
		'icon'=>'dashboard',
		'items' => array(
				array('label'=>'后台首页', 'icon'=>'home', 'url'=>$this->createUrl('site/index'),'active'=>$this->currentroute=='site/index'),
		),
		'visible'=>true,
	),
	array(
		'name' => '功能设置',
		'icon'=>'cog',
		'items' => array(
				array('label'=>'警情分类管理', 'icon'=>'picture', 'url'=>$this->createUrl('alarmconf/admin'),'active'=>Yii::app()->controller->id=='alarmconf'),
				array('label'=>'站默认参数管理', 'icon'=>'picture', 'url'=>$this->createUrl('stationparameter/admin'),'active'=>Yii::app()->controller->id=='stationparameter'),
				array('label'=>'组默认参数管理', 'icon'=>'picture', 'url'=>$this->createUrl('groupparameter/admin'),'active'=>Yii::app()->controller->id=='groupparameter'),
				array('label'=>'电池默认参数管理', 'icon'=>'picture', 'url'=>$this->createUrl('batteryparameter/admin'),'active'=>Yii::app()->controller->id=='batteryparameter'),
				array('label'=>'网站设置', 'icon'=>'globe', 'url'=>$this->createUrl('settings/index'),'active'=>$this->currentroute=='settings/index','visible'=>Yii::app()->user->checkAccess(BACKEND_ADMIN)),
		),
		'visible'=>Yii::app()->user->checkAccess(BACKEND_ADMIN),
	),
);
?>