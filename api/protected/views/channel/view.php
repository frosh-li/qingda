<?php
/* @var $this ArticleController */
/* @var $model Articles */

$this->breadcrumbs=array(
	'Articles'=>array('index'),
	$model->title,
);
?>
<div class="artView">
	<h3 class="artTitle"><?php echo $model->seotitle;?></h3>
	<p class="artTime">发表时间：<?php echo date('Y-m-d',$model->create_time);?></p>
	<div class="artText">
		<?php echo $model->content;?>
	</div>
</div>