<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . '-'.$channel;
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
	'Contact',
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
    
    <div class="panel">
	    <div class="panel-header">首页><?php echo $channel;?></div>
	    <div class="panel-main pd0x10">
	      <div class="list-list">
	        <ul>
             <?php
             if ($articles) {
               foreach ($articles as $key => $value) {
                   $url = $this->createUrl('article/view',array('id'=>$value->id));
                   if($value->type == 1){
                       echo '<li><span class="i-time">'.date('Y-m-d',$value->create_time).'</span><a href="'.$url.'"><font color="blue">'.$value->title.'</font></a></li>';
                   }else{
                       echo '<li><span class="i-time">'.date('Y-m-d',$value->create_time).'</span><a href="'.$url.'">'.$value->title.'</a></li>';
                   }
               }
             }
            ?>
	          </ul>
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