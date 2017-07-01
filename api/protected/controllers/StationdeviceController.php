<?php

class StationdeviceController extends Controller
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
            $sql = "select sd.*,s.site_name from {{station_device}} as sd
                LEFT JOIN  {{site}} as s on sd.sid=s.id where sd.id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = array(
                    'sid'=>$row['sid'],
                    'site_name'=>$row['site_name'],
                    'Device_name'=>$row['Supervisory_Device_name'],
                    'Device_fun'=>$row['Supervisory_Device_fun'],
                    'Device_Factory_Name'=>$row['Supervisory_Device_Factory_Name'],
                    'Device_Factory_Address'=>$row['Supervisory_Device_Factory_Address'],
                    'Device_Factory_PostCode'=>$row['Supervisory_Device_Factory_PostCode'],
                    'Device_Factory_website'=>$row['Supervisory_Device_Factory_website'],
                    'Device_Factory_Technology_cable_phone'=>$row['Supervisory_Device_Factory_Technology_cable_phone'],
                    'Device_Factory_Technology_cellphone'=>$row['Supervisory_Device_Factory_Technology_cellphone'],
                );
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该外控设备！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该外控设备！'
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
		$model= new StationDevice;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $sid=Yii::app()->request->getParam('sid','');
        $Device_name=Yii::app()->request->getParam('Device_name','');
        $Device_fun=Yii::app()->request->getParam('Device_fun','');
        $Device_Factory_Name=Yii::app()->request->getParam('Device_Factory_Name','');
        $Device_Factory_Address=Yii::app()->request->getParam('Device_Factory_Address','');
        $Device_Factory_PostCode=Yii::app()->request->getParam('Device_Factory_PostCode','');
        $Device_Factory_website=Yii::app()->request->getParam('Device_Factory_website','');
        $Device_Factory_Technology_cable_phone=Yii::app()->request->getParam('Device_Factory_Technology_cable_phone','');
        $Device_Factory_Technology_cellphone=Yii::app()->request->getParam('Device_Factory_Technology_cellphone','');

        if ($sid != 0) {
            $model->sid=$sid;
            $model->Supervisory_Device_name=$Device_name;
            $model->Supervisory_Device_fun=$Device_fun;
            $model->Supervisory_Device_Factory_Name=$Device_Factory_Name;
            $model->Supervisory_Device_Factory_Address=$Device_Factory_Address;
            $model->Supervisory_Device_Factory_PostCode=$Device_Factory_PostCode;
            $model->Supervisory_Device_Factory_website=$Device_Factory_website;
            $model->Supervisory_Device_Factory_Technology_cable_phone=$Device_Factory_Technology_cable_phone;
            $model->Supervisory_Device_Factory_Technology_cellphone = $Device_Factory_Technology_cellphone;
            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'sid'=>$model->sid,
                    'Supervisory_Device_name'=>$model->Supervisory_Device_name,
                );
            }else{
                var_dump($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'新建外控设备失败！'
                );
            }

        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'站点不能为空！'
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
        $id = Yii::app()->request->getParam('id' ,0);
        $model=$this->loadModel($id);

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        
        if ($model) {
            $sid=Yii::app()->request->getParam('sid','');
            $Device_name=Yii::app()->request->getParam('Device_name','');
            $Device_fun=Yii::app()->request->getParam('Device_fun','');
            $Device_Factory_Name=Yii::app()->request->getParam('Device_Factory_Name','');
            $Device_Factory_Address=Yii::app()->request->getParam('Device_Factory_Address','');
            $Device_Factory_PostCode=Yii::app()->request->getParam('Device_Factory_PostCode','');
            $Device_Factory_website=Yii::app()->request->getParam('Device_Factory_website','');
            $Device_Factory_Technology_cable_phone=Yii::app()->request->getParam('Device_Factory_Technology_cable_phone','');
            $Device_Factory_Technology_cellphone=Yii::app()->request->getParam('Device_Factory_Technology_cellphone','');
            if ($sid != '') {
                $sid!=''&&$model->sid=$sid;
                $Device_name!='' && $model->Supervisory_Device_name=$Device_name;
                $Device_fun!='' && $model->Supervisory_Device_fun=$Device_fun;
                $Device_Factory_Name!='' && $model->Supervisory_Device_Factory_Name=$Device_Factory_Name;
                $Device_Factory_Address!='' && $model->Supervisory_Device_Factory_Address=$Device_Factory_Address;
                $Device_Factory_PostCode!='' && $model->Supervisory_Device_Factory_PostCode=$Device_Factory_PostCode;
                $Device_Factory_website!='' && $model->Supervisory_Device_Factory_website=$Device_Factory_website;
                $Device_Factory_Technology_cable_phone!='' && $model->Supervisory_Device_Factory_Technology_cable_phone=$Device_Factory_Technology_cable_phone;
                $Device_Factory_Technology_cellphone!='' && $model->Supervisory_Device_Factory_Technology_cellphone=$Device_Factory_Technology_cellphone;
                if ($model->save()) {
                    $ret['data'] = array(
                        'id'=>$model->id,
                        'sid'=>$model->sid,
                        'Supervisory_Device_name'=>$model->Supervisory_Device_name,
                    );
                }else{
                    $ret['response'] = array(
                        'code'=>-1,
                        'msg'=>'新建外控设备失败！'
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
            ->select('sd.*,s.site_name')
            ->from('{{station_device}} sd')
            ->leftJoin('{{site}} s','sd.sid = s.id')
            ->limit($this->count)
            ->offset(($this->page-1)*$this->count)
            ->order('sd.id desc')
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
                $ret['data']['list'][] = array(
                    'id'=>$value['id'],
                    'site_name'=>$value['site_name'],
                    'sid'=>$value['sid'],
                    'Device_name'=>$value['Supervisory_Device_name'],
                    'Device_fun'=>$value['Supervisory_Device_fun'],
                    'Device_Factory_Name'=>$value['Supervisory_Device_Factory_Name'],
                    'Device_Factory_Address'=>$value['Supervisory_Device_Factory_Address'],
                    'Device_Factory_PostCode'=>$value['Supervisory_Device_Factory_PostCode'],
                    'Device_Factory_website'=>$value['Supervisory_Device_Factory_website'],
                    'Device_Factory_Technology_cable_phone'=>$value['Supervisory_Device_Factory_Technology_cable_phone'],
                    'Device_Factory_Technology_cellphone'=>$value['Supervisory_Device_Factory_Technology_cellphone'],
                );
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无外控设备数据！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new StationDevice('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StationDevice']))
			$model->attributes=$_GET['StationDevice'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return StationDevice the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=StationDevice::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param StationDevice $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='station-device-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
