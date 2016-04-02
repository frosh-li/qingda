<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=EDGE;IE=9; IE=8; IE=7;">
    <title>管理后台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <!-- Le styles -->
    <link href="<?php echo Yii::app()->baseUrl.'/admin/lib/bootstrap/css/bootstrap.min.css';?>" rel="stylesheet">
    <link href="<?php echo Yii::app()->baseUrl.'/admin/lib/bootstrap/css/bootstrap-responsive.min.css';?>" rel="stylesheet">
    <link href="<?php echo Yii::app()->baseUrl; ?>/admin/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->baseUrl.'/admin/css/theme.css';?>" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <link href="<?php echo Yii::app()->baseUrl; ?>/admin/lib/font-awesome/css/font-awesome-ie7.css" rel="stylesheet">
      <link href="<?php echo Yii::app()->baseUrl; ?>/admin/lib/bsie/css/bootstrap/bootstrap-ie6.css" rel="stylesheet">
      <link href="<?php echo Yii::app()->baseUrl; ?>/admin/lib/bsie/css/bootstrap/ie.css" rel="stylesheet">
      <script src="<?php echo Yii::app()->baseUrl; ?>/admin/lib/bsie/js/bootstrap-ie.js"></script>
      <script src="<?php echo Yii::app()->baseUrl; ?>/admin/lib/html5shiv.js"></script>
      <script src="<?php echo Yii::app()->baseUrl; ?>/admin/lib/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
      <?php echo $content; ?>
<?php Yii::app()->clientScript->registerCoreScript('jquery');?>
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo Yii::app()->baseUrl.'/admin/lib/bootstrap/js/bootstrap.js';?>"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        //init change display
        $('#collapse0').addClass('in');
        $('var').first().removeClass('icon-chevron-right').addClass('icon-chevron-down');
        $('body').tooltip({
            selector: '[rel=tooltip]'
        });
        
        //sidebar icon
        $('.accordion-toggle').bind('click',function(){
            if( $(this).hasClass('collapsed') ){
              $(this).find('var').removeClass('icon-chevron-down').addClass('icon-chevron-right');
              $('.accordion-toggle').not(this).find('var').removeClass('icon-chevron-down').addClass('icon-chevron-right');
            }else{
              $(this).find('var').removeClass('icon-chevron-right').addClass('icon-chevron-down');
              $('.accordion-toggle').not(this).find('var').removeClass('icon-chevron-down').addClass('icon-chevron-right');
            }
        });
    });
    </script>
  </body>
</html>