<?php

class StationpersonController extends Controller
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
	public function actionView()
	{
        $id = Yii::app()->request->getParam('id',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select * from my_sysuser where id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该站点人员！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该站点人员！'
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
		$model=new Sysuser;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        if(isset($_POST))  
        {

            $model->attributes=$_POST;  
            if($model->save()){
                $ret['data'] = array(
                    'id'=>$model->id
                );
            }
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
        $id = Yii::app()->request->getParam('id' ,0);
        $model=$this->loadModel($id);

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        if ($model) {
            $sid=Yii::app()->request->getParam('sid','');
            $Operater=Yii::app()->request->getParam('Operater','');
            $Operater_cellphone=Yii::app()->request->getParam('Operater_cellphone','');
            $Alarm_SMS_receive_cellphone=Yii::app()->request->getParam('Alarm_SMS_receive_cellphone','');
            $Alarm_SMS_receive_email=Yii::app()->request->getParam('Alarm_SMS_receive_email','');
            if ($sid) {

                $sid != '' && $model->sid = $sid;
                $Operater != '' && $model->Operater = $Operater;
                $Operater_cellphone != '' && $model->Operater_cellphone = $Operater_cellphone;
                $Alarm_SMS_receive_cellphone != '' && $model->Alarm_SMS_receive_cellphone = $Alarm_SMS_receive_cellphone;
                $Alarm_SMS_receive_email != '' && $model->Alarm_SMS_receive_email = $Alarm_SMS_receive_email;
                if ($model->save()) {
                    $ret['data'] = array(
                        'id'=>$model->id,
                        'sid'=>$model->sid,
                        'Operater'=>$model->Operater,
                    );
                }else{
                    $ret['response'] = array(
                        'code'=>-1,
                        'msg'=>'新建站点人员失败！'
                    );
                }
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'站点不能为空！'
                );
            }
        }
        echo json_encode($ret);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
        $id = Yii::app()->request->getParam('id' ,0);
        $ret['response'] = array(
            'code' => 0,
            'msg' => '删除设备成功!'
        );
        $ret['data'] = array();
        $result = $this->loadModel($id)->delete();

        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除设备失败！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $this->setPageCount();
        $ups = Yii::app()->db->createCommand()
            ->select('sp.*,s.site_name,r.rolename')
            ->from('{{sysuser}} sp')
            ->leftJoin('{{site}} s','sp.sn_key = s.serial_number')
            ->leftJoin('{{roles}} r', 'sp.role = r.id')
            ->limit($this->count)
            ->offset(($this->page-1)*$this->count)
            ->order('id desc')
            ->queryAll();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($ups) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($ups as $key=>$value){
                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点人员数据！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new StationPerson('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StationPerson']))
			$model->attributes=$_GET['StationPerson'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return StationPerson the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=StationPerson::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param StationPerson $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='station-person-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
