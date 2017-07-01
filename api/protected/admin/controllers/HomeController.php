<?php

class HomeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	// 招标信息
	public function actionIndex()
	{
		$model=new Articles();
		$id = 65;
		$channel = '招标信息';
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Articles']))
			$model->attributes=$_GET['Articles'];

		$this->render('admin',array(
			'model'=>$model,
			'id'=>$id,
			'channel'=>$channel,
		));
	}
	// 新闻资讯
	public function actionNews()
	{
		$model=new Articles();
		$id = 62;
		$channel = '新闻资讯';
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Articles']))
			$model->attributes=$_GET['Articles'];

		$this->render('admin',array(
			'model'=>$model,
			'id'=>$id,
			'channel'=>$channel,
		));
	}
	// 公示栏
	public function actionNotice()
	{
		$model=new Articles();
		$id = 64;
		$channel = '公示栏';
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Articles']))
			$model->attributes=$_GET['Articles'];

		$this->render('admin',array(
			'model'=>$model,
			'id'=>$id,
			'channel'=>$channel,
		));
	}
	// 信息公开规章制度
	public function actionRules()
	{
		$model=new Articles();
		$id = 63;
		$channel = '信息公开规章制度';
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Articles']))
			$model->attributes=$_GET['Articles'];

		$this->render('admin',array(
			'model'=>$model,
			'id'=>$id,
			'channel'=>$channel,
		));
	}
	// 信息公开规章制度
	public function actionNewinfo()
	{
		$model=new Articles();
		$id = 272;
		$channel = '最新信息';
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Articles']))
			$model->attributes=$_GET['Articles'];

		$this->render('admin',array(
			'model'=>$model,
			'id'=>$id,
			'channel'=>$channel,
		));
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
	public function actionCreate($cid)
	{
		$model=new Articles;
		$model->cid = $cid;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$where = ' pid=0 and ishidden=1';
		$channel = Channels::model()->findAll($where);

		$cidarr = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{channels}}')
            ->where('id='.$cid)
            ->queryRow();

		if(isset($_POST['Articles']))
		{
			$model->attributes=$_POST['Articles'];
			$model->tag = trim($model->tag,',');
			$model->metakeywords = $model->tag;
			if($model->save()){
				switch ($model->cid) {
					case 62:
						$this->redirect(array('news'));
						break;
					case 63:
						$this->redirect(array('rules'));
						break;
					case 64:
						$this->redirect(array('notice'));
						break;
					case 64:
						$this->redirect(array('index'));
					case 272:
						$this->redirect(array('newinfo'));
					default:
						$this->redirect(array('index'));
						break;
				}
				// $this->redirect(array('admin'));
				// $this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'channel'=>$channel,
			'cidarr'=>$cidarr,
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
		$where = ' pid=0 and ishidden=1';
		if (Yii::app()->user->catalog != 0) {
			$where .= ' and id in ('.Yii::app()->user->catalog.') ';
		}
		$channel = Channels::model()->findAll($where);
		
		if(isset($_POST['Articles']))
		{
			$model->attributes=$_POST['Articles'];
			$model->tag = trim($model->tag,',');
			$model->metakeywords = $model->tag;
			if($model->save()){
				switch ($model->cid) {
					case 62:
						$this->redirect(array('news'));
						break;
					case 63:
						$this->redirect(array('rules'));
						break;
					case 64:
						$this->redirect(array('notice'));
						break;
					case 64:
						$this->redirect(array('index'));
					case 272:
						$this->redirect(array('newinfo'));
					default:
						$this->redirect(array('index'));
						break;
				}
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
	// Uncomment the following methods and override them if needed
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
				'actions'=>array('changepwd'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('view','index','newinfo','news','notice','rules','admin','delete','create','update'),
				// 'users'=>array('admin'),
				'roles'=>array(ROLE_ADMIN),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
}