<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
switch ($code) {
	case 404:
		$message = '您访问的页面不存在！';
		break;
	case 403:
		$message = '您未被授权访问此页面！';
		break;
	default:
		$message = '访问页面发生错误！';
		break;
}
?>
<div class="page-title"><i class="i_icon"></i> 错误 </div>
<div class="pd20 left">
	<div class="panel ">
		<div class="alert alert-danger">
		  <div class="i-info"><?php echo CHtml::encode($message); ?></div>
		</div>
	</div>
</div>