<?php

class HomeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	public function actionIndex()
	{
		$this->render('index');
	}


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

		// $articles = Yii::app()->db->createCommand()
	 //            ->select('*')
	 //            ->from('{{articles}}')
	 //            ->where('cid='.$id)
	 //            ->limit(10)
	 //            ->order('create_time desc')
	 //           	->queryAll();

	    $pager->pageSize = 20;
		$criteria->order = 'type desc ,id desc';
		$pager->applyLimit($criteria);
		$model = Articles::model()->findAll($criteria);

	    $pids = Yii::app()->db->createCommand()
				    ->select('id,pid,channeltype,systemtype,ishidden,title,link,target')
				    ->from('{{channels}}')
				    ->where('pid=0')
				    ->order('id asc,ordernum desc')	
				    ->queryAll();
		$pidarr = array();
		if ($pids) {
			foreach ($pids as $key => $value) {
				$pidarr[$value['id']]= $value['title'];
			}
		}
		
		$news = Yii::app()->db->createCommand()
	            ->select('*')
	            ->from('{{articles}}')
	            ->where('cid=62')
	            ->limit(1)
	            ->order('create_time desc')
	           	->queryRow();

		$this->render('view',array(
			'articles'=>$model,
			'pid'=>$channel->pid,
			'pidstr' =>$pidarr[$channel->pid],
			'channel' =>$channel->title,
			'pager'=>$pager,
			'news'=>$news,
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