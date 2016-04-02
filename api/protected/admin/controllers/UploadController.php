<?php

class UploadController extends Controller
{
	public function actionIndex()
	{
		$msg = '上传成功';
		if (Yii::app()->user->isGuest) {
			$msg = '您还没有登录';
			echo  json_encode(array('error' => 1, 'message' => $msg));
			exit;
		}
		//允许上传的扩展
		$ext_arr = array(
			'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
			'flash' => array('swf', 'flv'),
			'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
			'file' => array('doc', 'docx','pdf', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
		);
		$dir = Yii::app()->request->getParam('dir','image');
		$images = CUploadedFile::getInstancesByName('imgFile');
		if (isset($images) && count($images) > 0) {
        	foreach ($images as $key => $pic) {
        		if ($pic->hasError) {
        			$ret['error'] = 1;
					$ret['message'] = $pic->error;
        			echo json_encode($ret);
        			exit;
        		}
        		$ext = strtolower($pic->getExtensionName());
        		if (in_array($ext, $ext_arr[$dir]) === false) {
        			$ret['error'] = 1;
					$ret['message'] = '上传文件扩展名是不允许的扩展名。';
        			echo json_encode($ret);
        			exit;
        		}
        		// $filename = 'image_'.uniqid().'_'.$key.".".$pic->getExtensionName();
        		$filename =  preg_replace('/[\s]/', '', $pic->name);
        		
        		$full_filename = $filename;
        		if(!defined('SAE_TMP_PATH')){
        			$full_filename = $this->getUploadDir($dir).$filename;
        		}
				$picurl = $pic->saveAs($full_filename);
				if ($picurl){
					if(!defined('SAE_TMP_PATH')){
						$url = $this->getUploadBase($dir).$filename;
					}else{
						$url = $picurl;
					}
					$ret['error'] = 0;
					$ret['url'] = $url;
				}else{
					$ret['error'] = 1;
					$ret['message'] = 'file save error';
				}
				echo json_encode($ret);
        	}
		}
	}
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}