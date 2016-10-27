<?php

class CompanyinfoController extends Controller
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
            $sql = "select * from {{company_info}}
                    where id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该公司信息！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该公司信息！'
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
		$model=new CompanyInfo;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'新建成功'
        );
        $ret['data'] = array();
        $company_name=Yii::app()->request->getParam('company_name','');
        $company_address=Yii::app()->request->getParam('company_address','');
        $supervisor_phone=Yii::app()->request->getParam('supervisor_phone','');
        $supervisor_name=Yii::app()->request->getParam('supervisor_name','');
        $longitude=Yii::app()->request->getParam('longitude','');
        $latitude=Yii::app()->request->getParam('latitude','');
        $station_num=Yii::app()->request->getParam('station_num','');
        $area_level=Yii::app()->request->getParam('area_level','');
        $network_type=Yii::app()->request->getParam('network_type','');
        $bandwidth=Yii::app()->request->getParam('bandwidth','');
        $ipaddr=Yii::app()->request->getParam('ipaddr','');
        $computer_brand=Yii::app()->request->getParam('computer_brand','');
        $computer_os=Yii::app()->request->getParam('computer_os','');
        $computer_conf=Yii::app()->request->getParam('computer_conf','');
        $browser_name=Yii::app()->request->getParam('browser_name','');
        $server_capacity=Yii::app()->request->getParam('server_capacity','');
        $server_type=Yii::app()->request->getParam('server_type','');
        $cloud_address=Yii::app()->request->getParam('cloud_address','');
        $backup_period=Yii::app()->request->getParam('backup_period','');
        $backup_type=Yii::app()->request->getParam('backup_type','');
        $supervisor_depname=Yii::app()->request->getParam('supervisor_depname','');
        $monitor_name1=Yii::app()->request->getParam('monitor_name1','');
        $monitor_phone1=Yii::app()->request->getParam('monitor_phone1','');
        $monitor_name2=Yii::app()->request->getParam('monitor_name2','');
        $monitor_phone2=Yii::app()->request->getParam('monitor_phone2','');
        $monitor_name3=Yii::app()->request->getParam('monitor_name3','');
        $monitor_phone3=Yii::app()->request->getParam('monitor_phone3','');
        $monitor_tel1=Yii::app()->request->getParam('monitor_tel1','');
        $monitor_tel2=Yii::app()->request->getParam('monitor_tel2','');
        $modify_time=Yii::app()->request->getParam('modify_time',date('Y-m-d H:i:s'));

        $area_name=Yii::app()->request->getParam('area_name','');
        $parent_area=Yii::app()->request->getParam('parent_area','');
        $duty_status=Yii::app()->request->getParam('duty_status','');
        $owner=Yii::app()->request->getParam('owner','');
        $owner_phone=Yii::app()->request->getParam('owner_phone','');
        $manage=Yii::app()->request->getParam('manage','');
        $viewer=Yii::app()->request->getParam('viewer','');

        if ($company_name) {
            $company_name !='' && $model->company_name=$company_name;
            $company_address !='' && $model->company_address=$company_address;
            $supervisor_phone !='' && $model->supervisor_phone=$supervisor_phone;
            $supervisor_name !='' && $model->supervisor_name=$supervisor_name;
            $longitude !='' && $model->longitude=$longitude;
            $latitude !='' && $model->latitude=$latitude;
            $station_num !='' && $model->station_num=$station_num;
            $area_level !='' && $model->area_level=$area_level;
            $network_type !='' && $model->network_type=$network_type;
            $bandwidth !='' && $model->bandwidth=$bandwidth;
            $ipaddr !='' && $model->ipaddr=$ipaddr;
            $computer_brand !='' && $model->computer_brand=$computer_brand;
            $computer_os !='' && $model->computer_os=$computer_os;
            $computer_conf !='' && $model->computer_conf=$computer_conf;
            $browser_name !='' && $model->browser_name=$browser_name;
            $server_capacity !='' && $model->server_capacity=$server_capacity;
            $server_type !='' && $model->server_type=$server_type;
            $cloud_address !='' && $model->cloud_address=$cloud_address;
            $backup_period !='' && $model->backup_period=$backup_period;
            $backup_type !='' && $model->backup_type=$backup_type;
            $supervisor_depname !='' && $model->supervisor_depname=$supervisor_depname;
            $monitor_name1 !='' && $model->monitor_name1=$monitor_name1;
            $monitor_phone1 !='' && $model->monitor_phone1=$monitor_phone1;
            $monitor_name2 !='' && $model->monitor_name2=$monitor_name2;
            $monitor_phone2 !='' && $model->monitor_phone2=$monitor_phone2;
            $monitor_name3 !='' && $model->monitor_name3=$monitor_name3;
            $monitor_phone3 !='' && $model->monitor_phone3=$monitor_phone3;
            $monitor_tel1 !='' && $model->monitor_tel1=$monitor_tel1;
            $monitor_tel2 !='' && $model->monitor_tel2=$monitor_tel2;
            $modify_time !='' && $model->modify_time=$modify_time;

            $area_name !='' && $model->area_name=$area_name;
            $parent_area !='' && $model->parent_area=$parent_area;
            $duty_status !='' && $model->duty_status=$duty_status;
            $owner !='' && $model->owner=$owner;
            $owner_phone !='' && $model->owner_phone=$owner_phone;
            $manage !='' && $model->manage=$manage;
            $viewer !='' && $model->viewer=$viewer;


            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'company_name'=>$model->company_name,
                    'company_address'=>$model->company_address,
                );
            }else{
                var_dump($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'新建公司信息数据失败！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'新建公司信息数据失败！'
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
        $company_name=Yii::app()->request->getParam('company_name','');
        $company_address=Yii::app()->request->getParam('company_address','');
        $supervisor_phone=Yii::app()->request->getParam('supervisor_phone','');
        $supervisor_name=Yii::app()->request->getParam('supervisor_name','');
        $longitude=Yii::app()->request->getParam('longitude','');
        $latitude=Yii::app()->request->getParam('latitude','');
        $station_num=Yii::app()->request->getParam('station_num','');
        $area_level=Yii::app()->request->getParam('area_level','');
        $network_type=Yii::app()->request->getParam('network_type','');
        $bandwidth=Yii::app()->request->getParam('bandwidth','');
        $ipaddr=Yii::app()->request->getParam('ipaddr','');
        $computer_brand=Yii::app()->request->getParam('computer_brand','');
        $computer_os=Yii::app()->request->getParam('computer_os','');
        $computer_conf=Yii::app()->request->getParam('computer_conf','');
        $browser_name=Yii::app()->request->getParam('browser_name','');
        $server_capacity=Yii::app()->request->getParam('server_capacity','');
        $server_type=Yii::app()->request->getParam('server_type','');
        $cloud_address=Yii::app()->request->getParam('cloud_address','');
        $backup_period=Yii::app()->request->getParam('backup_period','');
        $backup_type=Yii::app()->request->getParam('backup_type','');
        $supervisor_depname=Yii::app()->request->getParam('supervisor_depname','');
        $monitor_name1=Yii::app()->request->getParam('monitor_name1','');
        $monitor_phone1=Yii::app()->request->getParam('monitor_phone1','');
        $monitor_name2=Yii::app()->request->getParam('monitor_name2','');
        $monitor_phone2=Yii::app()->request->getParam('monitor_phone2','');
        $monitor_name3=Yii::app()->request->getParam('monitor_name3','');
        $monitor_phone3=Yii::app()->request->getParam('monitor_phone3','');
        $monitor_tel1=Yii::app()->request->getParam('monitor_tel1','');
        $monitor_tel2=Yii::app()->request->getParam('monitor_tel2','');
        $modify_time=Yii::app()->request->getParam('modify_time',date('Y-m-d H:i:s'));

        $area_name=Yii::app()->request->getParam('area_name','');
        $parent_area=Yii::app()->request->getParam('parent_area','');
        $duty_status=Yii::app()->request->getParam('duty_status','');
        $owner=Yii::app()->request->getParam('owner','');
        $owner_phone=Yii::app()->request->getParam('owner_phone','');
        $manage=Yii::app()->request->getParam('manage','');
        $viewer=Yii::app()->request->getParam('viewer','');


        if ($model) {
            $company_name !='' && $model->company_name=$company_name;
            $company_address !='' && $model->company_address=$company_address;
            $supervisor_phone !='' && $model->supervisor_phone=$supervisor_phone;
            $supervisor_name !='' && $model->supervisor_name=$supervisor_name;
            $longitude !='' && $model->longitude=$longitude;
            $latitude !='' && $model->latitude=$latitude;
            $station_num !='' && $model->station_num=$station_num;
            $area_level !='' && $model->area_level=$area_level;
            $network_type !='' && $model->network_type=$network_type;
            $bandwidth !='' && $model->bandwidth=$bandwidth;
            $ipaddr !='' && $model->ipaddr=$ipaddr;
            $computer_brand !='' && $model->computer_brand=$computer_brand;
            $computer_os !='' && $model->computer_os=$computer_os;
            $computer_conf !='' && $model->computer_conf=$computer_conf;
            $browser_name !='' && $model->browser_name=$browser_name;
            $server_capacity !='' && $model->server_capacity=$server_capacity;
            $server_type !='' && $model->server_type=$server_type;
            $cloud_address !='' && $model->cloud_address=$cloud_address;
            $backup_period !='' && $model->backup_period=$backup_period;
            $backup_type !='' && $model->backup_type=$backup_type;
            $supervisor_depname !='' && $model->supervisor_depname=$supervisor_depname;
            $monitor_name1 !='' && $model->monitor_name1=$monitor_name1;
            $monitor_phone1 !='' && $model->monitor_phone1=$monitor_phone1;
            $monitor_name2 !='' && $model->monitor_name2=$monitor_name2;
            $monitor_phone2 !='' && $model->monitor_phone2=$monitor_phone2;
            $monitor_name3 !='' && $model->monitor_name3=$monitor_name3;
            $monitor_phone3 !='' && $model->monitor_phone3=$monitor_phone3;
            $monitor_tel1 !='' && $model->monitor_tel1=$monitor_tel1;
            $monitor_tel2 !='' && $model->monitor_tel2=$monitor_tel2;
            $modify_time !='' && $model->modify_time=$modify_time;

            $area_name !='' && $model->area_name=$area_name;
            $parent_area !='' && $model->parent_area=$parent_area;
            $duty_status !='' && $model->duty_status=$duty_status;
            $owner !='' && $model->owner=$owner;
            $owner_phone !='' && $model->owner_phone=$owner_phone;
            $manage !='' && $model->manage=$manage;
            $viewer !='' && $model->viewer=$viewer;


            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'company_name'=>$model->company_name,
                    'company_address'=>$model->company_address,
                );
            }else{
                var_dump($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'修改公司信息数据失败！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'修改公司信息数据失败！'
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
        $id = Yii::app()->request->getParam('id' ,0);
        $ret['response'] = array(
            'code' => 0,
            'msg' => '删除公司信息成功!'
        );
        $ret['data'] = array();
        $result = $this->loadModel($id)->delete();

        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除公司信息失败！'
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
            ->from('{{company_info}}')
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
                'msg' => '暂无公司信息数据！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CompanyInfo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CompanyInfo']))
			$model->attributes=$_GET['CompanyInfo'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CompanyInfo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CompanyInfo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CompanyInfo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='company-info-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
