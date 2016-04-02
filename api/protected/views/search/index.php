<?php
/* @var $this SearchController */
$this->pageTitle=Yii::app()->name.'-'.'搜索'.$keyword;
$this->searchkey = $keyword;
$morenews = $this->createUrl('home/view',array('id'=>62));
if ($news) {
	$newsurl = $this->createUrl('article/view',array('id'=>$news['id']));
	if (preg_match('/<img.*?src="(.*?)"/', $news['content'],$match)) {
		$newspic = $match[1];
	}else{
		$newspic = Yii::app()->baseUrl.'/images/jtwd.jpg';
	}
}
$this->breadcrumbs=array(
	'Search',
);
?>
<!-- body -->
<div class="pager_center">
	<!-- 左边 -->
	<div class="pager_center_left">
		<div class="zhengcebg">
			<h3>新闻资讯<span>News</span></h3>
			<?php
			if ($news) {
				?>
				<div class="i-photo">
					<a href="<?php echo $newsurl;?>">
						<img src="<?php echo $newspic;?>" alt="">
					</a>
				</div>
				<div class="i-title"><a target="_blank" href="<?php echo $newsurl;?>"><?php echo $news['title'];?></a></div>
				<p class="more"><span><a href="<?php echo $morenews;?>">更多新闻>></a></span></p>
			<?php
			}else{
				echo '<p>暂无信息</p>';
			}
			?>
		</div>
		<div class="com_ClearAll"></div>
		<div class="webinfo">
			<h3>公 示 栏 <span>Publicity</span><span class="newmore"><a href="<?php echo $this->createUrl('home/view',array('id'=>64));?>">more</a></span>
			</h3>
			<div class="title1">
				<?php $this->widget('ext.NewsWidget',array('limit'=>2,'cid'=>64));?>
			</div>
		</div>
		<div class="webinfo">
			<h3>招标信息<span>Invitation</span><span class="newmore"><a href="<?php echo $this->createUrl('home/view',array('id'=>65));?>">more</a></span>
			</h3>
			<div class="title1">
				<?php $this->widget('ext.NewsWidget',array('limit'=>2,'cid'=>65));?>
			</div>
		</div>
		<div class="imglink">
			<a href="mailto:xxgk@cumtb.edu.cn"><img src="<?php echo Yii::app()->baseUrl.'/images/link_advice.png';?>" width="210" alt="表格下载"></a>
			<a href="<?php echo Yii::app()->baseUrl.'/docs/xxgksqb.doc';?>"><img src="<?php echo Yii::app()->baseUrl.'/images/link_table_download.png';?>" alt="意见箱"></a>
		</div>
	</div>
	<!-- 右边 -->
	<div class="pager_center_right">
		<div style="margin: 2px 10px 2px 2px;height: 30px;line-height: 30px;">
			<form id="advsearchform" name="advsearchform" action="<?php echo $this->createAbsoluteUrl('/search/advanced');?>" method="get" style="display:inline">
				<?php echo $option;?>
				<input type="text" class="search_input" name="searchTitle" id="advkeyword" value="<?php echo $this->searchkey;?>" />
				<input type="submit" class="search_submit" id="advsubmitbtn" value="搜索" />
			</form>
		</div>
		<div class="panel">
			<div class="panel-header">首页>搜索关键词：<font color="red"><?php echo $keyword;?></font></div>
			<div class="panel-main pd0x10">
				<div class="list-list">
						<?php
						if ($articles) {
							echo '<ul>';
							foreach ($articles as $key => $value) {
								$title = preg_replace("/($keyword)/i", '<font color="red">\\1</font>', $value->title);
								$url = $this->createUrl('article/view',array('id'=>$value->id,'keyword'=>$keyword));
								echo '<li><span class="i-time">'.date('Y-m-d',$value->create_time).'</span><a href="'.$url.'">'.$title.'</a></li>';
							}
							echo '</ul>';
						}else{
							if($keyword ==''){
								echo '<div style="margin:10px 20px;">搜索关键词不能为空</div>';
							}else{
								echo '<div style="margin:10px 20px;"> 没有找到“'.$keyword.'”相关结果'.'</div>';
							}
						}
						?>
				</div>
			</div>

		</div>
		<div class="com_ClearAll"></div>
		<div id="pager">
			<?php
			$this->widget('CLinkPager',array(
					'header'=>'',
					//'cssFile'=>false,
					'firstPageLabel' => '首页',
					'lastPageLabel' => '末页',
					'prevPageLabel' => '上一页',
					'nextPageLabel' => '下一页',
					'pages' => $pager,
					'maxButtonCount'=>30
				)
			);
			?>
		</div>
	</div>
	<div class="com_ClearAll"></div>
</div>
<script>
	$("#advsubmitbtn").click(function(e){
		e.preventDefault();
		var advkeyword = $("#advkeyword").val();
		advkeyword = advkeyword.replace(/^\s+|\s+$/gm,'');
		if(advkeyword == ''){
			alert('请输入要查询的内容');
			return;
		}else{
			$("#advsearchform").submit();
		}
	});
</script>