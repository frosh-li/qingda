<?php

class ChannelController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$channel = $this->loadModel($id);

		$criteria = new CDbCriteria();
		$criteria->compare('cid',$id);
		
		$count = Articles::model()->count($criteria);
		$pager = new CPagination($count);

		$pager->pageSize = 20;
		$criteria->order = 'type desc ,id desc';
		$pager->applyLimit($criteria);
		$model = Articles::model()->findAll($criteria);
		
		if (!$model) {
			$criteria = new CDbCriteria();
			$criteria->compare('scid',$id);
			
			$count = Articles::model()->count($criteria);
			$pager = new CPagination($count);

			$pager->pageSize = 20;
			$criteria->order = 'id desc';
			$pager->applyLimit($criteria);
			$model = Articles::model()->findAll($criteria);
		}
	    $pid = Yii::app()->db->createCommand()
				    ->select('id,pid,channeltype,systemtype,ishidden,title,link,target')
				    ->from('{{channels}}')
				    ->where('id='.$channel->pid)
				    ->order('id asc,ordernum desc')	
				    ->queryRow();
		$pidstr = $cidstr = '';
		if ($pid) {
			$pidstr = '<a href="'.$this->createUrl('channel/class',array('id'=>$channel->pid)).'" >'.$pid['title'].'</a>';
		}
		
		if ($channel->cid != 0 ) {
			$cid = Yii::app()->db->createCommand()
				    ->select('id,pid,channeltype,systemtype,ishidden,title,link,target')
				    ->from('{{channels}}')
				    ->where('id='.$channel->cid)
				    ->order('id asc,ordernum desc')	
				    ->queryRow();
		}
		if (isset($cid) && $cid) {
			$cidstr = '<a href="'.$this->createUrl('channel/view',array('id'=>$channel->cid)).'" >'.$cid['title'].'</a>>';
		}
		$hascids = Yii::app()->db->createCommand()
				    ->select('id,pid,channeltype,systemtype,ishidden,title,link,target')
				    ->from('{{channels}}')
				    ->where('cid='.$channel->id)
				    ->order('id asc,ordernum desc')
				    ->limit(10)
				    ->queryAll();
		$this->render('viewlist',array(
			'articles'=>$model,
			// 'pid'=>$channel->pid,
			'pidstr' =>$pidstr,
			'cidstr'=>$cidstr,
			'channel' =>$channel,
			'hascids' =>$hascids,
			'pager'=>$pager,
		));
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewbak($id)
	{
		$channel = $this->loadModel($id);
		
		$this->render('view',array(
			'model'=>$channel,
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Channels;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Channels']))
		{
			$model->attributes=$_POST['Channels'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
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
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$articles = Yii::app()->db->createCommand()
	            ->select('*')
	            ->from('{{articles}}')
	            ->limit(10)
	            // ->where('cid not in(64,65,66)')
	            ->order('create_time desc')
	           	->queryAll();
		
		$this->render('index',array(
			'articles'=>$articles,
			'channel' =>'',
		));
	}
	/**
	 * Lists all models.
	 */
	public function actionClass($id)
	{
		$channel = $this->loadModel($id);
		$criteria = new CDbCriteria();
		$criteria->compare('pid',$id);
		
		$count = Articles::model()->count($criteria);
		$pager = new CPagination($count);

		// $articles = Yii::app()->db->createCommand()
	 //            ->select('*')
	 //            ->from('{{articles}}')
	 //            ->where('cid='.$id)
	 //            ->limit(10)
	 //            ->order('create_time desc')
	 //           	->queryAll();

	    $pager->pageSize = 20;
		$criteria->order = 'id desc';
		$pager->applyLimit($criteria);
		$model = Articles::model()->findAll($criteria);

	    $pid = Yii::app()->db->createCommand()
				    ->select('id,pid,channeltype,systemtype,ishidden,title,link,target')
				    ->from('{{channels}}')
				    ->where('id='.$channel->pid)
				    ->order('id asc,ordernum desc')	
				    ->queryRow();
		$pidstr = $cidstr = '';
		if ($pid) {
			$pidstr = $pid['title'].'>';
		}
		
		if ($channel->cid != 0 ) {
			$cid = Yii::app()->db->createCommand()
				    ->select('id,pid,channeltype,systemtype,ishidden,title,link,target')
				    ->from('{{channels}}')
				    ->where('id='.$channel->cid)
				    ->order('id asc,ordernum desc')	
				    ->queryRow();
		}
		if (isset($cid) && $cid) {
			$cidstr = $cid['title'].'>';
		}
		$hascids = Yii::app()->db->createCommand()
				    ->select('id,pid,channeltype,systemtype,ishidden,title,link,target')
				    ->from('{{channels}}')
				    ->where('cid='.$channel->id)
				    ->order('id asc,ordernum desc')
				    ->limit(10)
				    ->queryAll();
		$this->render('class',array(
			'articles'=>$model,
			// 'pid'=>$channel->pid,
			'pidstr' =>$pidstr,
			'cidstr'=>$cidstr,
			'channel' =>$channel,
			'hascids' =>$hascids,
			'pager'=>$pager,
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
