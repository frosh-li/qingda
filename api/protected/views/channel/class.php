<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . '-'.$channel->title;
$this->breadcrumbs=array(
	'Contact',
);
?>

<!-- body -->
<div class="pager_center">
    <!-- 左边 -->
    <div class="pager_center_left">
        <div class="channellist">
            <h3>相关栏目</h3>
            <div class="title1">
                <?php $this->widget('ext.RelateChannelWidget',array('limit'=>10,'pid'=>$channel->pid,'cid'=>$channel->cid));?>
            </div>
        </div>
        <?php
        if ($channel->cid) {
        ?>
        <div class="channellist">
            <h3>上级栏目</h3>
            <div class="title1">
                <?php $this->widget('ext.RelateChannelWidget',array('limit'=>10,'pid'=>$channel->pid,'cid'=>0));?>
            </div>
        </div>
        <?php
        }
        if ($hascids) {
          # code...
        }
        ?>
        <div class="webinfo">
            <h3>公 示 栏 <span>Publicity</span><span class="newmore"><a target="_blank" href="<?php echo $this->createUrl('home/view',array('id'=>64));?>">more</a></span>
            </h3>
            <div class="title1">
                <?php $this->widget('ext.NewsWidget',array('limit'=>2,'cid'=>64));?>
            </div>
        </div>
        <div class="webinfo">
            <h3>招标信息<span>Invitation</span><span class="newmore"><a target="_blank" href="<?php echo $this->createUrl('home/view',array('id'=>65));?>">more</a></span>
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
	    <div class="panel-header"><a href="/index.php">首页</a>><?php echo $pidstr;?><?php echo $cidstr;?><?php echo $channel->title;?></div>
	    <div class="panel-main pd0x10">
	      <div class="list-list">
	        <ul>
             <?php
             if ($articles) {
               foreach ($articles as $key => $value) {
                $url = $this->createUrl('article/view',array('id'=>$value->id));
                 echo '<li><span class="i-time">'.date('Y-m-d',$value->create_time).'</span><a href="'.$url.'">'.$value->title.'</a></li>';
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