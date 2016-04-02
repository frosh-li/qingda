<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE;IE=9; IE=8; IE=7;">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $this->pageTitle;?></title>
        <link href="<?php echo Yii::app()->baseUrl.'/css/reset.css';?>" rel="Stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->baseUrl.'/css/common.css';?>" rel="Stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->baseUrl.'/css/pager.css?id=5';?>" rel="Stylesheet" type="text/css">
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->
    </head>
<body>
<?php echo $content;?>
</body>
</html>