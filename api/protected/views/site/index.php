<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
// $morenews = $this->createUrl('home/view',array('id'=>62));
$morenews = 'http://news.cumtb.edu.cn/';

if ($news) {
    $newsurl = $this->createUrl('article/view',array('id'=>$news['id']));
    if (preg_match('/<img.*?src="(.*?)"/', $news['content'],$match)) {
        $newspic = $match[1];
    }else{
        $newspic = Yii::app()->baseUrl.'/images/jtwd.jpg';
    }
}

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
            <p class="more"><span><a target="_blank" href="<?php echo $morenews;?>">更多新闻>></a></span></p>
        <?php
        }else{
            echo '<p>暂无信息</p>';
        }
        ?>
        </div>
        
        <div class="com_ClearAll"></div>
        <div class="webinfo">
            <h3>公 示 栏 <span>Publicity</span> <span class="newmore"><a href="<?php echo $this->createUrl('home/view',array('id'=>64));?>">more</a></span>
            </h3>
            <div class="title1">
                <?php $this->widget('ext.NewsWidget',array('limit'=>2,'cid'=>64));?>
            </div>
        </div>
        <div class="webinfo">
            <h3>招标信息<span>Invitation</span> <span class="newmore"><a href="<?php echo $this->createUrl('home/view',array('id'=>65));?>">more</a></span>
            </h3>
            <div class="title1">
                <?php $this->widget('ext.NewsWidget',array('limit'=>2,'cid'=>65));?>
            </div>
        </div>
        <div class="imglink">
            <a href="mailto:xxgk@cumtb.edu.cn"><img src="<?php echo Yii::app()->baseUrl.'/images/link_advice.png';?>"  width="210" alt="表格下载"></a>
            <a href="<?php echo Yii::app()->baseUrl.'/docs/xxgksqb.doc';?>"><img src="<?php echo Yii::app()->baseUrl.'/images/link_table_download.png';?>" alt="意见箱"></a>
        </div>
    </div>
    <!-- 右边 -->
    <div class="pager_center_right">
        <div class="centlistb com_fl">
            <h3 class="pager_title">
                最新信息 <span>News</span>
                <a href="<?php echo $this->createUrl('home/view',array('id'=>272));?>">more</a>
             </h3>
             <div class="list-title">
               <?php $this->widget('ext.NewInfoWidget',array('limit'=>10,'cid'=>272));?>
            </div>
        </div>
        <div class="com_ClearAll"></div>
        <div class="bigbody">
        <div class="infobody">
            <h3 class="bigh">信息公开指南</h3>
            <p>为了保障公民、法人和其他组织依法获取学校信息，
                根据《中华人民共和国政府信息公开条例》和《高等学校信息公开办法》等有关规定，结合《中国矿业大学（北京）信息公开实施办法（试行）》，制定本指南。
                需要获得学校信息公开服务的公民、法人和其他组织，建议阅读本指南。<a target="_blank" href="<?php echo $this->createUrl('about/view',array('id'=>1));?>">详细>></a></p>
        </div>
        <div class="infobody">
            <h3 class="bigh" id="rulecolor">信息公开规章制度<span><a href="<?php echo $this->createUrl('home/view',array('id'=>63));?>">more</a></span></h3>
           <div class="list-title">
                <?php $this->widget('ext.NewsWidget',array('limit'=>5,'cid'=>63));?>
            </div>
        </div>
        <div class="com_ClearAll"></div>
        <div class="bigul">
            <h3 class="bigh">公开信息</h3>
            <div class="infoopen">
            <?php $this->widget('ext.LinksWidget');?>
            </div>
        </div>
        </div>
    </div>
    <div class="com_ClearAll"></div>
</div>
