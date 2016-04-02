<?php
header("Content-type: text/html; charset=utf-8");
session_start();
// change the following paths if necessary
define('ROOT_PATH', dirname(__FILE__));

$yii=ROOT_PATH.'/framework/yii.php';
$config=ROOT_PATH.'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
