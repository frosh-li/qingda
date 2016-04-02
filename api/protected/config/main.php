<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
define('SITE_URL', 'http://api.aiaiapp.com');
define("IMG_BASE_URL",'http://aiai.qiniudn.com/');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'清大领航',
    'language' =>'zh-CN',
	//'defaultController'=>'adminmsg',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
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
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'format'=>array(
                'datetimeFormat' => 'Y-m-d h:i', 
        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
			//'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		'db'=>require(dirname(__FILE__).'/database.php'),
        'bms'=>require(dirname(__FILE__).'/bmsdatabase.php'),
		'cache' => array(
            'class' => 'system.caching.CFileCache',
		    'directoryLevel' => 1,
        ),
        'mcache' => array(
		    'class'=>'system.caching.CMemCache',
             'servers'=>array(
                 array('host'=>'localhost', 'port'=>11211, 'weight'=>60),
             ),
        ),
		'config' => array(
         	'class' => 'application.extensions.EConfig',
         	'configTableName' => '{{config}}',
			'strictMode' => false,
            'autoCreateConfigTable'=>false,
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
				// uncomment the following to show log messages on web pages
				//array(
				//	'class'=>'CWebLogRoute',
				//),
			),

		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'mustcode'=>1,
	),
);