<?php

class BmsinfoController extends Controller
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
            $sql = "select * from {{bms_info}}
                    where id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该BMS！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该BMS！'
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
		$model=new BmsInfo;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'新建成功'
        );
        $ret['data'] = array();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        $bms_company = Yii::app()->request->getParam('bms_company','');
        $bms_device_addr=Yii::app()->request->getParam('bms_device_addr','');
        $bms_postcode=Yii::app()->request->getParam('bms_postcode','');
        $bms_url=Yii::app()->request->getParam('bms_url','');
        $bms_tel=Yii::app()->request->getParam('bms_tel','');
        $bms_phone=Yii::app()->request->getParam('bms_phone','');
        $bms_service_phone=Yii::app()->request->getParam('bms_service_phone','');
        $bms_service_name=Yii::app()->request->getParam('bms_service_name','');
        $bms_service_url=Yii::app()->request->getParam('bms_service_url','');
        $bms_version=Yii::app()->request->getParam('bms_version','');
        $bms_update_mark=Yii::app()->request->getParam('bms_update_mark','');
        $bms_mark=Yii::app()->request->getParam('bms_mark','');
        $modify_time=Yii::app()->request->getParam('modify_time',date('Y-m-d H:i:s'));

        if ($bms_company) {
            $bms_company != '' && $model->bms_company = $bms_company;
            $bms_device_addr != '' && $model->bms_device_addr = $bms_device_addr;
            $bms_postcode != '' && $model->bms_postcode = $bms_postcode;
            $bms_url != '' && $model->bms_url = $bms_url;
            $bms_tel != '' && $model->bms_tel = $bms_tel;
            $bms_phone != '' && $model->bms_phone = $bms_phone;
            $bms_service_phone != '' && $model->bms_service_phone = $bms_service_phone;
            $bms_service_name != '' && $model->bms_service_name = $bms_service_name;
            $bms_service_url != '' && $model->bms_service_url = $bms_service_url;
            $bms_version != '' && $model->bms_version = $bms_version;
            $bms_update_mark != '' && $model->bms_update_mark = $bms_update_mark;
            $bms_mark != '' && $model->bms_mark = $bms_mark;
            $modify_time != '' && $model->modify_time = $modify_time;
            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'bms_company'=>$model->bms_company,
                    'bms_device_addr'=>$model->bms_device_addr,
                );
            }else{
                var_dump($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'新建BMS信息数据失败！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'新建BMS信息数据失败！'
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
            'msg'=>'新建成功'
        );
        $ret['data'] = array();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $bms_company=Yii::app()->request->getParam('bms_company','');
        $bms_device_addr=Yii::app()->request->getParam('bms_device_addr','');
        $bms_postcode=Yii::app()->request->getParam('bms_postcode','');
        $bms_url=Yii::app()->request->getParam('bms_url','');
        $bms_tel=Yii::app()->request->getParam('bms_tel','');
        $bms_phone=Yii::app()->request->getParam('bms_phone','');
        $bms_service_phone=Yii::app()->request->getParam('bms_service_phone','');
        $bms_service_name=Yii::app()->request->getParam('bms_service_name','');
        $bms_service_url=Yii::app()->request->getParam('bms_service_url','');
        $bms_version=Yii::app()->request->getParam('bms_version','');
        $bms_update_mark=Yii::app()->request->getParam('bms_update_mark','');
        $bms_mark=Yii::app()->request->getParam('bms_mark','');
        $modify_time=Yii::app()->request->getParam('modify_time',date('Y-m-d H:i:s'));
        if ($model) {
            $bms_company != '' && $model->bms_company = $bms_company;
            $bms_device_addr != '' && $model->bms_device_addr = $bms_device_addr;
            $bms_postcode != '' && $model->bms_postcode = $bms_postcode;
            $bms_url != '' && $model->bms_url = $bms_url;
            $bms_tel != '' && $model->bms_tel = $bms_tel;
            $bms_phone != '' && $model->bms_phone = $bms_phone;
            $bms_service_phone != '' && $model->bms_service_phone = $bms_service_phone;
            $bms_service_name != '' && $model->bms_service_name = $bms_service_name;
            $bms_service_url != '' && $model->bms_service_url = $bms_service_url;
            $bms_version != '' && $model->bms_version = $bms_version;
            $bms_update_mark != '' && $model->bms_update_mark = $bms_update_mark;
            $bms_mark != '' && $model->bms_mark = $bms_mark;
            $modify_time != '' && $model->modify_time = $modify_time;
            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'bms_company'=>$model->bms_company,
                    'bms_device_addr'=>$model->bms_device_addr,
                );
            }else{
                var_dump($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'修改BMS信息数据失败！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'修改BMS信息数据失败！'
            );
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
            'msg' => '删除BMS信息成功!'
        );
        $ret['data'] = array();
        $result = $this->loadModel($id)->delete();

        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除BMS信息失败！'
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
            ->select('*')
            ->from('{{bms_info}}')
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
                'msg' => '暂无BMS信息数据！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BmsInfo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BmsInfo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BmsInfo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bms-info-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
