<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
//role define
defined('ROLE_ADMIN') or define('ROLE_ADMIN','0');
defined('ROLE_SALE') or define('ROLE_SALE','1');
defined('ROLE_CHANNL') or define('ROLE_CHANNL','2');
defined('ROLE_OPERATION') or define('ROLE_OPERATION','3');

$backend = dirname( dirname( __FILE__ ) );
$frontend = dirname( $backend );
Yii::setpathofalias( "backend", $backend );
Yii::setPathOfAlias('bootstrap', $frontend.'/extensions/bootstrap');

$frontendArray = require( $frontend."/config/main.php" );
unset( $frontendArray['components']['urlManager'] );
unset( $frontendArray['import'] );
$backmain =  array(
	//'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'清大领航',
	"language" => "zh_cn",
	'sourceLanguage' => 'zh_cn',
	"charset" => "utf-8",
	"timeZone" => "Asia/Chongqing",
	//controll application path
	"basePath" => $frontend,
	"viewPath" => $backend."/views",
	"controllerPath" => $backend."/controllers",
	"runtimePath" => $backend."/../runtime/backend",
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'backend.models.*',
		'backend.components.*',
		'backend.extensions.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'sa',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>false,
			'class' => 'WebUser',
		),
		// uncomment the following to enable URLs in path-format
		'format'=>array(
                'datetimeFormat' => 'Y-m-d H:i', 
        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
			//'urlSuffix'=>'.html',
			/*
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			*/
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',

			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning,info',
				),
				// uncomment the following to show log messages on web pages
				array(
	               'class'=>'CProfileLogRoute',
	                'levels'=>'error,warning,info',//设置错误级别
	            ),
	            
				array(
					'class'=>'CWebLogRoute',
				),
				
			),

		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
);
$config = new CMap( $frontendArray );
$config->mergeWith( $backmain );
$config = $config->toArray( );
return $config;