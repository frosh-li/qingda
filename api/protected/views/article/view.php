<?php
/* @var $this ArticleController */
/* @var $model Articles */

$this->breadcrumbs=$breadcrumbs;
?>
<div id="breadcrumbs">
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'separator'=>'>',
			'homeLink'=>CHtml::link('首页', Yii::app()->homeUrl),
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
		</div>
<div class="artView">
	
	<h3 class="artTitle"><?php echo $model->title;?></h3>
	<p class="artTime">发表时间：<?php echo date('Y-m-d',$model->create_time);?> 文章出处：<?php echo $user->department;?></p>
	<div class="artText">
		<?php
			//if($keyword != ''){
			//	$model->content = preg_replace("/($keyword)/i", '<font color="red">\\1</font>', $model->content);
			//}
			echo $model->content;
		?>
	</div>
</div>