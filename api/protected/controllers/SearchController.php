<?php

class SearchController extends Controller
{
	public function actionIndex()
	{
		$keyword = strip_tags(trim(Yii::app()->request->getParam('searchTitle','')));
		$model = null;
		$pager = new CPagination();
		$criteria = new CDbCriteria();
		if($keyword !=''){
			$criteria->compare('title',$keyword,true);
			$count = Articles::model()->count($criteria);
			$pager = new CPagination($count);

			$pager->pageSize = 20;
			$criteria->order = 'id desc';
			$pager->applyLimit($criteria);
			$model = Articles::model()->findAll($criteria);
		}
		$news = Yii::app()->db->createCommand()
			->select('*')
			->from('{{articles}}')
			->where('cid=62')
			->limit(1)
			->order('create_time desc')
			->queryRow();
		$option = $this->createOption(1);
		$this->render('index',array(
			'articles'=>$model,
			'pager'=>$pager,
			'news'=>$news,
			'option'=>$option,
			'keyword'=>$keyword,
		));
	}
	public function actionAdvanced()
	{
		$keyword = strip_tags(trim(Yii::app()->request->getParam('searchTitle','')));
		$type = strip_tags(trim(Yii::app()->request->getParam('type',1)));
		$criteria = new CDbCriteria();
		$model = null;
		$pager = new CPagination();
		if($keyword != ''){
			if($type == 1){
				$criteria->compare('title',$keyword,true);
			}else if($type == 2){
				$criteria->compare('content',$keyword,true);
			}

			$count = Articles::model()->count($criteria);
			$pager = new CPagination($count);

			$pager->pageSize = 20;
			$criteria->order = 'id desc';
			$pager->applyLimit($criteria);
			$model = Articles::model()->findAll($criteria);
		}

		$news = Yii::app()->db->createCommand()
			->select('*')
			->from('{{articles}}')
			->where('cid=62')
			->limit(1)
			->order('create_time desc')
			->queryRow();
		$option = $this->createOption($type);
		$this->render('index',array(
			'articles'=>$model,
			'pager'=>$pager,
			'news'=>$news,
			'option'=>$option,
			'keyword'=>$keyword,
		));
	}

	public function createOption($type)
	{
		$str = '<select name="type" id="">';
		if($type == 1){
			$str .= '<option value="1" selected>标题</option>';
		}else{
			$str .= '<option value="1">标题</option>';
		}
		if($type == 2){
			$str .= '<option value="2" selected>正文</option>';
		}else{
			$str .= '<option value="2">正文</option>';
		}
		$str .= '</select>';
		return $str;
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