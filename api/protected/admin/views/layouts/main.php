<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE;IE=9; IE=8; IE=7;">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Yii::app()->name;?></title>
  <link href="<?php echo Yii::app()->baseUrl.'/admin/lib/bootstrap/css/bootstrap.min.css';?>" rel="stylesheet">
  <link href="<?php echo Yii::app()->baseUrl.'/admin/lib/bootstrap/css/bootstrap-responsive.min.css';?>" rel="stylesheet">
  <link href="<?php echo Yii::app()->baseUrl; ?>/admin/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->baseUrl.'/admin/css/style.css';?>" rel="stylesheet" type="text/css">
  <!--[if lte IE 7]>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl.'/admin/lib/bsie/bootstrap/css/bootstrap-ie6.css';?>">
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl.'/admin/lib/bsie/bootstrap/css/ie.css';?>">
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/admin/lib/font-awesome/css/font-awesome-ie7.css" rel="stylesheet">
  <![endif]-->
</head>
<body>
<div class="layout_header">
  <div class="header">
    <div class="h_logo">
        <a href="#" title="<?php echo Yii::app()->name;?>">
        </a></div>
    <div class="h_nav"> <span class="hi"> 欢迎你，<?php echo Yii::app()->user->name;?>！</span>
    	<span class="link">
        <a href="/" target="_blank">首页</a>
        <?php
        if (Yii::app()->user->role ==ROLE_ADMIN) {
          ?>
        <a href="<?php echo Yii::app()->createUrl('settings/index');?>"><i class="icon16 icon16-setting"></i> 设置</a>
       <?php
        }
        ?>
        <a href="<?php echo Yii::app()->createUrl('site/logout');?>"><i class="icon16 icon16-power"></i> 注销</a>
      </span> </div>
    <div class="clear"></div>
  </div>
</div>
<div class="layout_leftnav">
  <div class="inner">
    <div class="nav-vertical">
      <ul class="accordion">
        <?php
          foreach($this->sidebarmenu as $key=>$sub){
            if (!$sub['visible']) {
              continue;
            }
        ?>
        <li> <a href="#"><i class="icon-<?php echo $sub['icon'];?>"></i><?php echo $sub['name'];?><span></span></a>
          <ul class="sub-menu">
        <?php 
           foreach ($sub['items'] as $k => $v) {
            if (!isset($v['visible']) || $v['visible']) {
               if ($v['active']) {
                 echo '<li><a href="'.$v['url'].'" class="active">'.$v['label'].'</a></li>';
               }else{
                echo '<li><a href="'.$v['url'].'">'.$v['label'].'</a></li>';
               }
             }
           }
        ?>
          </ul>
        </li>
        <?php } ?>
      </ul>
  </div>
  </div>
</div>
<div class="layout_rightmain">
  <div class="inner">
    <?php echo $content; ?>
  </div>
</div>
<div class="layout_footer">&copy; 2013-2014 qingda.com <?php echo Yii::app()->name;?>版权所有</div>

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
<script src="<?php echo Yii::app()->baseUrl.'/admin/lib/bootstrap/js/bootstrap.js';?>"></script>
<script src="<?php echo Yii::app()->baseUrl.'/admin/js/common.js';?>" type="text/javascript"></script>
<!--[if lte IE 7]>
  <script type="text/javascript" src="<?php echo Yii::app()->baseUrl.'/admin/lib/bsie/bootstrap/js/bootstrap-ie.js';?>"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->baseUrl.'/admin/js/ie.js';?>"></script>
<![endif]-->
<script type="text/javascript">
    $(document).ready(function() {
      $('body').tooltip({
            selector: '[rel=tooltip]'
        });
      // Store variables
      var accordion_head = $('.accordion > li > a'),
        accordion_body = $('.accordion li > .sub-menu');
      // Open the first tab on load
      //accordion_head.first().addClass('active').next().slideDown('normal');
      $('.accordion li > .sub-menu .active').parent().parent().prev().addClass('active').next().slideDown('fast');
      // Click function
      accordion_head.on('click', function(event) {
        // Disable header links
        event.preventDefault();
        // Show and hide the tabs on click
        if ($(this).attr('class') != 'active'){
          accordion_body.slideUp('normal');
          $(this).next().stop(true,true).slideToggle('normal');
          accordion_head.removeClass('active');
          $(this).addClass('active');
        }
      });
    });
  </script> 
</body>
</html>
