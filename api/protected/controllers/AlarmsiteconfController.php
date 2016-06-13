<?php

class AlarmsiteconfController extends Controller
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
		$model=new AlarmSiteconf;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AlarmSiteconf']))
		{
			$model->attributes=$_POST['AlarmSiteconf'];
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
	public function actionUpdate()
	{
        $id = Yii::app()->request->getParam('id',0);
        $status = Yii::app()->request->getParam('status',0);
        $sn_key=Yii::app()->request->getParam('sn_key','');
        $category=Yii::app()->request->getParam('category','');
        $type=Yii::app()->request->getParam('type','');
        $content=Yii::app()->request->getParam('content','');
        $suggest=Yii::app()->request->getParam('suggest','');
        $send_email=Yii::app()->request->getParam('send_email','');
        $send_msg=Yii::app()->request->getParam('send_msg','');
        $alarm_type=Yii::app()->request->getParam('alarm_type','');
        $type_value=Yii::app()->request->getParam('type_value','');
        $operator=Yii::app()->request->getParam('operator','');
        $judge_type=Yii::app()->request->getParam('judge_type','');
        $can_ignore=Yii::app()->request->getParam('can_ignore','');
        $alarm_code=Yii::app()->request->getParam('alarm_code','');

        $ret['response'] = array(
            'code' => 0,
            'msg' => '修改成功！'
        );
        $ret['data'] = array();
        $model=$this->loadModel($id);
        if ($model) {
            $sn_key!='' && $model->sn_key=$sn_key;
            $category!='' && $model->category=$category;
            $type!='' && $model->type=$type;
            $content!='' && $model->content=$content;
            $suggest!='' && $model->suggest=$suggest;
            $send_email!='' && $model->send_email=$send_email;
            $send_msg!='' && $model->send_msg=$send_msg;
            $alarm_type!='' && $model->alarm_type=$alarm_type;
            $type_value!='' && $model->type_value=$type_value;
            $operator!='' && $model->operator=$operator;
            $judge_type!='' && $model->judge_type=$judge_type;
            $can_ignore!='' && $model->can_ignore=$can_ignore;
            $alarm_code!='' && $model->alarm_code=$alarm_code;
            $status!='' && $model->status=$status;
            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'sn_key'=>$model->sn_key,
                    'status'=>$model->status,
                );
            }else{
                var_dump($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'保存失败！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'状态不能为空！'
            );
        }
        echo json_encode($ret);
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
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        //$id = Yii::app()->request->getParam('id' ,0);
        $sid = Yii::app()->request->getParam('sid' ,0);
        $category = Yii::app()->request->getParam('category' ,1);

        $sql = "select asi.*,s.site_name from {{alarm_siteconf}} asi
                LEFT JOIN  {{site}} s on asi.sn_key=s.serial_number
                where asi.sn_key=".$sid." and asi.category=".$category;

        $rows =  Yii::app()->db->createCommand($sql)->queryAll();
        if($rows){
            foreach ($rows as  $key=> $value ) {
                $ret['data']['list'][] = $value;
            }
        }else{
        	// 如果没有门限数据，默认取门限数据插入到对应表中
        	$sql = "select * from {{alarm_conf}} where category=$category";
        	$rows = Yii::app()->db->createCommand($sql)->queryAll();

        	foreach ($rows as  $key=> $value ) {
        		$value['sn_key'] = $sid;
        		$value['status'] = 0;
                
                unset($value['id']);
                Yii::app()->db->createCommand()->insert('my_alarm_siteconf',$value);
                $value['id'] = Yii::app()->db->getLastInsertID();
                $ret['data']['list'][] = $value;
            }
        }


        echo json_encode($ret);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AlarmSiteconf('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AlarmSiteconf']))
			$model->attributes=$_GET['AlarmSiteconf'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AlarmSiteconf the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AlarmSiteconf::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AlarmSiteconf $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='alarm-siteconf-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
