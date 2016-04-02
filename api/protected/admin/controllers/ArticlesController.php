<?php

class ArticlesController extends Controller
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
				'actions'=>array('index','admin','delete','view','create','update'),
				// 'users'=>array('@'),
				'roles'=>array(ROLE_ADMIN,ROLE_CHANNLADMIN,ROLE_INFOADMIN),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('all'),
				// 'users'=>array('admin'),
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
		$model=new Articles;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$roles = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{roles}}')
            ->where('uid='.Yii::app()->user->id)
            ->queryAll();
        $rolestr = '';
        $role = array();
        if ($roles) {
        	foreach ($roles as $key => $value) {
        		$role[] = $value['pid'];
        	}
        	$rolestr = implode(',', $role);
        }
        

		$where = ' pid=0 and ishidden=0';
		if (Yii::app()->user->role != 0) {
            if($rolestr == ''){
                throw new CHttpException(403,'您还没有权限！');
                Yii::app()->end();
            }
			$where .= ' and id in ('.$rolestr.') ';
		}
		$channel = Channels::model()->findAll($where);

		if(isset($_POST['Articles']))
		{
			$model->attributes=$_POST['Articles'];
			$model->tag = trim($model->tag,',');
			$model->metakeywords = $model->tag;
			if($model->save()){
				$this->redirect(array('admin'));
				// $this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'channel'=>$channel,
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
		
        $roles = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{roles}}')
            ->where('uid='.Yii::app()->user->id)
            ->queryAll();
        $rolestr = '';
        if ($roles) {
        	foreach ($roles as $key => $value) {
        		$role[] = $value['pid'];
        	}
        	$rolestr = implode(',', $role);
        }
		$where = ' pid=0 and ishidden=0';
		if (Yii::app()->user->role !== ROLE_ADMIN) {
             if($rolestr == ''){
                throw new CHttpException(403,'您还没有权限！');
                Yii::app()->end();
            }
			$where .= ' and id in ('.$rolestr.') ';
		}
		$channel = Channels::model()->findAll($where);
		
		if(isset($_POST['Articles']))
		{
			$model->attributes=$_POST['Articles'];
			$model->tag = trim($model->tag,',');
			$model->metakeywords = $model->tag;
			if($model->save()){
				$this->redirect(array('admin'));
				// $this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'channel'=>$channel,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		if (Yii::app()->user->role != 0) {
			if ($model->userid != Yii::app()->user->id) {
				throw new CHttpException(403,'您未被授权删除此文章！');
				Yii::app()->end();
			}
		}
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Articles');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Articles('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Articles']))
			$model->attributes=$_GET['Articles'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionAll()
	{
		$model=new Articles('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Articles']))
			$model->attributes=$_GET['Articles'];

		$this->render('all',array(
			'model'=>$model,
		));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Articles the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Articles::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Articles $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='articles-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
