<?php

class ChannelsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

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
				'actions'=>array('index','pcreate','oupdate','my','pupdate','supdate','first','screate','second','third','view','create','update','subuserthree','subuser','subthree','subcate','Subchannel'),
				'users'=>array('@'),
				// 'roles'=>array(ROLE_ADMIN),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array(ROLE_ADMIN),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Channels;
		$model->pid = Yii::app()->request->getParam('id',0);
		$model->channeltype = 2;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Channels']))
		{
			$channels = $_POST['Channels'];
			
			$model->attributes=$_POST['Channels'];
			echo $model->cid;
			is_array($_POST['Channels']['positions'])?$model->positions = implode(',', $_POST['Channels']['positions']):'';
			if (strpos($model->link,'http') !== false) {
				$model->link = '/'.trim($model->link,'/');
			}
			if($model->save()){
				$this->redirect(array('admin'));
			}else{
				throw new CHttpException(500,'channels create error.');
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionScreate()
	{
		$model=new Channels;
		$model->pid = Yii::app()->request->getParam('id',0);
		$model->channeltype = 2;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Channels']))
		{
			$channels = $_POST['Channels'];
			
			$model->attributes=$_POST['Channels'];
			echo $model->cid;
			is_array($_POST['Channels']['positions'])?$model->positions = implode(',', $_POST['Channels']['positions']):'';
			if (strpos($model->link,'http') !== false) {
				$model->link = '/'.trim($model->link,'/');
			}
			if($model->save()){
				$this->redirect(array('admin'));
			}else{
				throw new CHttpException(500,'channels create error.');
			}
		}

		$this->render('screate',array(
			'model'=>$model,
		));
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionSupdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Channels']))
		{
			$model->attributes=$_POST['Channels'];
			if ($model->cid =='empty') {
				$model->cid = 0;
			}
			is_array($_POST['Channels']['positions'])?$model->positions = implode(',', $_POST['Channels']['positions']):'';
			if (strpos($model->link,'http') !== false) {
				$model->link = '/'.trim($model->link,'/');
			}
			if($model->save()){
				$this->redirect(array('second'));
				// $this->redirect(array('view','id'=>$model->id));
			}else{
				throw new CHttpException(500,'channels update error.');
			}
		}

		$this->render('supdate',array(
			'model'=>$model,
		));
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionPupdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Channels']))
		{
			$model->attributes=$_POST['Channels'];
			if ($model->cid =='empty') {
				$model->cid = 0;
			}
			is_array($_POST['Channels']['positions'])?$model->positions = implode(',', $_POST['Channels']['positions']):'';
			if (strpos($model->link,'http') !== false) {
				$model->link = '/'.trim($model->link,'/');
			}
			if($model->save()){
				$this->redirect(array('first'));
				// $this->redirect(array('view','id'=>$model->id));
			}else{
				throw new CHttpException(500,'channels update error.');
			}
		}

		$this->render('pupdate',array(
			'model'=>$model,
		));
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionOupdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Channels']))
		{
			$model->attributes=$_POST['Channels'];

			// is_array($_POST['Channels']['positions'])?$model->positions = implode(',', $_POST['Channels']['positions']):'';
			if($model->save()){
				$this->redirect(array('my'));
				// $this->redirect(array('view','id'=>$model->id));
			}else{
				throw new CHttpException(500,'channels update error.');
			}
		}

		$this->render('oupdate',array(
			'model'=>$model,
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionPcreate()
	{
		$model=new Channels;
		$model->pid = Yii::app()->request->getParam('id',0);
		$model->channeltype = 2;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Channels']))
		{
			$channels = $_POST['Channels'];
			
			$model->attributes=$_POST['Channels'];
			echo $model->cid;
			is_array($_POST['Channels']['positions'])?$model->positions = implode(',', $_POST['Channels']['positions']):'';
			if (strpos($model->link,'http') !== false) {
				$model->link = '/'.trim($model->link,'/');
			}
			if($model->save()){
				$this->redirect(array('admin'));
			}else{
				throw new CHttpException(500,'channels create error.');
			}
		}

		$this->render('pcreate',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Channels']))
		{
			$model->attributes=$_POST['Channels'];
			if ($model->cid =='empty') {
				$model->cid = 0;
			}
			is_array($_POST['Channels']['positions'])?$model->positions = implode(',', $_POST['Channels']['positions']):'';
			if (strpos($model->link,'http') !== false) {
				$model->link = '/'.trim($model->link,'/');
			}
			if($model->save()){
				$this->redirect(array('third'));
				// $this->redirect(array('view','id'=>$model->id));
			}else{
				throw new CHttpException(500,'channels update error.');
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	/**
	 * [actionSubcate description]
	 * @return [type] [description]
	 */
	public function actionSubcate()
	{
		$id = Yii::app()->request->getParam('pid',0);
		if ($id ==0) {
			return false;
		}

		$roles = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{roles}}')
            ->where('uid='.Yii::app()->user->id)
            ->queryAll();
        $rolestr = '';
        if ($roles) {
        	foreach ($roles as $key => $value) {
        		$role[] = $value['cid'];
        	}
        	$rolestr = implode(',', $role);
        }
		$where = '';
		if (Yii::app()->user->role !== ROLE_ADMIN) {
			$where = ' and id in ('.$rolestr.') ';
		}
		$data=Channels::model()->findAll('pid=:pid and cid =0 '.$where, 
                  array(':pid'=>$id));
 		
	    $data=CHtml::listData($data,'id','title');
	    foreach($data as $value=>$name)
	    {
	        echo CHtml::tag('option',
	                   array('value'=>$value),CHtml::encode($name),true);
	    }
	}
	/**
	 * [actionSubcate description]
	 * @return [type] [description]
	 */
	public function actionSubthree()
	{
		$id = Yii::app()->request->getParam('pid',0);
		if ($id ==0) {
			return false;
		}
		$roles = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{roles}}')
            ->where('uid='.Yii::app()->user->id)
            ->queryAll();
        $rolestr = '';
        if ($roles) {
        	foreach ($roles as $key => $value) {
        		$role[] = $value['channel'];
        	}
        	$rolestr = implode(',', $role);
        }
		$where = '';
		if (Yii::app()->user->role !== ROLE_ADMIN) {
			$where = ' and id in ('.$rolestr.') ';
		}
		
		$data=Channels::model()->findAll('cid=:cid'.$where, 
                  array(':cid'=>$id));
 		
	    $data=CHtml::listData($data,'id','title');
	    foreach($data as $value=>$name)
	    {
	        echo CHtml::tag('option',
	                   array('value'=>$value),CHtml::encode($name),true);
	    }
	}
	/**
	 * [actionSubcate description]
	 * @return [type] [description]
	 */
	public function actionSubchannel()
	{
		$articles = $_POST['Channels'];

		$id = (int)$articles['pid'];
		if ($id ==0) {
			return false;
		}
		$data=Channels::model()->findAll('pid=:pid', 
                  array(':pid'=>$id));
 		
	    $data=CHtml::listData($data,'id','title');
	    foreach($data as $value=>$name)
	    {
	        echo CHtml::tag('option',
	                   array('value'=>$value),CHtml::encode($name),true);
	    }
	}
	/**
	 * [actionSubcate description]
	 * @return [type] [description]
	 */
	public function actionSubuser()
	{
		$articles = $_POST['Adminuser'];

		$id = (int)$articles['catalog'];
		if ($id ==0) {
			return false;
		}
		$data=Channels::model()->findAll('pid=:pid and cid =0', 
                  array(':pid'=>$id));
 		
	    $data=CHtml::listData($data,'id','title');
	    foreach($data as $value=>$name)
	    {
	        echo CHtml::tag('option',
	                   array('value'=>$value),CHtml::encode($name),true);
	    }
	}
	/**
	 * [actionSubcate description]
	 * @return [type] [description]
	 */
	public function actionSubuserthree()
	{
		$articles = $_POST['Adminuser'];

		$id = (int)$articles['pid'];
		if ($id ==0) {
			return false;
		}
		$data=Channels::model()->findAll('cid=:cid', 
                  array(':cid'=>$id));
 		
	    $data=CHtml::listData($data,'id','title');
	    foreach($data as $value=>$name)
	    {
	        echo CHtml::tag('option',
	                   array('value'=>$value),CHtml::encode($name),true);
	    }
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Channels');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Channels('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Channels']))
			$model->attributes=$_GET['Channels'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionFirst()
	{
		$model=new Channels('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Channels']))
			$model->attributes=$_GET['Channels'];

		$this->render('first',array(
			'model'=>$model,
			'flag'=>1,
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionMy()
	{
		$model=new Channels('my');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Channels']))
			$model->attributes=$_GET['Channels'];

		$this->render('my',array(
			'model'=>$model,
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionSecond()
	{
		$model=new Channels('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Channels']))
			$model->attributes=$_GET['Channels'];

		$this->render('second',array(
			'model'=>$model,
			'flag'=>2,
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionThird()
	{
		$model=new Channels('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Channels']))
			$model->attributes=$_GET['Channels'];

		$this->render('third',array(
			'model'=>$model,
			'flag'=>3,
		));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Channels the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Channels::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Channels $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='channels-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
