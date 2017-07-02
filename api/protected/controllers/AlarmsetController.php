<?php

class AlarmsetController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';
    public function init()
    {
        if (!YII_DEBUG) {
            if ($_SESSION['role'] != 1) {
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => '您没有权限操作！'
                );
                $ret['data'] = array();
                echo json_encode($ret);
                Yii::app()->end();
            }
        }
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
        $id = Yii::app()->request->getParam('id',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select * from {{alarmset}} where id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该用户！'
            );
        }
        echo json_encode($ret);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $area = Yii::app()->request->getParam('area' ,'');

        $username = Yii::app()->request->getParam('username' ,'');
        $phone = Yii::app()->request->getParam('phone' ,'');
        $phone_status = Yii::app()->request->getParam('phone_status' ,1);
        $email = Yii::app()->request->getParam('email' ,'');
        $email_status = Yii::app()->request->getParam('email_status' ,1);

        $areamodel = Trees::model()->findByAttributes('title',$area);
        $model=new Alarmset;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($areamodel) {
            $model->aid = $areamodel->id;
            $model->area = $areamodel->title;
            $model->username = $username;
            $model->phone = $phone;
            $model->phone_status = $phone_status;
            $model->email = $email;
            $model->email_status = $email_status;
            if ($model->save()) {
                $ret['data'] = array(
                    'aid'=>$model->aid,
                );
            }else{
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => '保存出错！'
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有该区域！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
        $id = Yii::app()->request->getParam('id',0);
		$model=$this->loadModel($id);

        $area = Yii::app()->request->getParam('area' ,'');
        $username = Yii::app()->request->getParam('username' ,'');
        $phone = Yii::app()->request->getParam('phone' ,'');
        $phone_status = Yii::app()->request->getParam('phone_status' ,1);
        $email = Yii::app()->request->getParam('email' ,'');
        $email_status = Yii::app()->request->getParam('email_status' ,1);

        $areamodel = Trees::model()->findByAttributes('title',$area);

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($areamodel) {
            $model->aid = $areamodel->id;
            $model->area = $areamodel->title;
            $model->username = $username;
            $model->phone = $phone;
            $model->phone_status = $phone_status;
            $model->email = $email;
            $model->email_status = $email_status;
            if ($model->save()) {
                $ret['data'] = array(
                    'aid'=>$model->aid,
                );
            }else{
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => '保存出错！'
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有该区域！'
            );
        }

        echo json_encode($ret);
	}

    public function actionUpdateMsg(){
        $id = Yii::app()->request->getParam('id',0);
        $field = Yii::app()->request->getParam('field',0);
        $val = Yii::app()->request->getParam('val',0);
        $sql = 'update my_station_alert_desc set my_station_alert_desc.'.$field.'='.$val." where id=".$id;
        $update = Yii::app()->db->createCommand($sql)->execute();
        $ret['response'] = array(
                'code' => 0,
                'msg' => '更新成功'
        );
        echo json_encode($ret);
    }
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
        $id = Yii::app()->request->getParam('id',0);
        $result = $this->loadModel($id)->delete();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除失败！'
            );
        }else{
            $ret['data'] = array(
                'uid'=>$id
            );
        }
        echo json_encode($result);
		}

    public function actionConfig(){
        $sql = "select * from my_station_alert_desc";
        $alertSets = Yii::app()->db->createCommand("select * from my_station_alert_desc order by id asc")->queryAll();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        foreach($alertSets as $key=>$value){
            $ret['data']['list'][] = $value;
        }
        echo json_encode($ret);
    }
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $this->setPageCount();
        $sysusers = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{alarmset}}')
            ->limit($this->count)
            ->offset(($this->page-1)*$this->count)
            ->order('id desc')
            ->queryAll();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sysusers) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sysusers as $key=>$value){
                $ret['data']['list'][] = $value;
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无设置！'
            );
        }
        echo json_encode($ret);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Alarmset('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Alarmset']))
			$model->attributes=$_GET['Alarmset'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Alarmset the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Alarmset::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Alarmset $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='alarmset-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
