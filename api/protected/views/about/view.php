<?php
/* @var $this ArticleController */
/* @var $model Articles */

$this->breadcrumbs=array(
	'Articles'=>array('index'),
	$model->title,
);
?>
<div class="artView">
	<h3 class="artTitle"><?php echo $model->title;?></h3>
	<p class="artTime">发表时间：<?php echo date('Y-m-d',$model->update_time);?> 文章出处：信息公开办公室 </p>
	<div class="artText">
		<?php echo $model->content;?>
	</div>
</div>