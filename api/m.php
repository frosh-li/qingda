<?php

// change the following paths if necessary
define('ROOT_PATH', dirname(__FILE__));
require(ROOT_PATH.'/protected/config/roles.php');

$yii=ROOT_PATH.'/framework/yii.php';
$config=ROOT_PATH.'/protected/admin/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
