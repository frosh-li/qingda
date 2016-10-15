<?php

class SitesController extends Controller
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
            $sql = "select * from {{site}} where id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该站点！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该站点！'
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
        $password = Yii::app()->request->getParam('password' ,'');
        if (!$this->checkPassword($password)) {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'密码错误！'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }

        $sid=Yii::app()->request->getParam('sid',0);
        $serial_number=Yii::app()->request->getParam('serial_number','');

        $trows = Yii::app()->db->createCommand('select sid from my_site where serial_number='.$serial_number)->queryAll();
        if($trows){
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'站MAC地址重复'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }

        $site_name=Yii::app()->request->getParam('site_name','');
        $StationFullChineseName=Yii::app()->request->getParam('StationFullChineseName','');
        
        $site_property=Yii::app()->request->getParam('site_property','');
        $site_location=Yii::app()->request->getParam('site_location','');
        $site_chname=Yii::app()->request->getParam('site_chname','');
        $site_longitude=Yii::app()->request->getParam('site_longitude','');
        $site_latitude=Yii::app()->request->getParam('site_latitude','');
        $ipaddress=Yii::app()->request->getParam('ipaddress','');
        $ipaddress_method=Yii::app()->request->getParam('ipaddress_method','');
        $site_control_type=Yii::app()->request->getParam('site_control_type','');
        $postal_code=Yii::app()->request->getParam('postal_code','');
        $emergency_phone=Yii::app()->request->getParam('emergency_phone','');
        $emergency_person=Yii::app()->request->getParam('emergency_person','');
        $remark=Yii::app()->request->getParam('remark','');
        $aid=Yii::app()->request->getParam('area','');
        $groups=Yii::app()->request->getParam('groups','');
        $batteries=Yii::app()->request->getParam('batteries','');
        $battery_status=Yii::app()->request->getParam('battery_status','');
        $bms_install_date = Yii::app()->request->getParam('bms_install_date','');
        $group_collect_type=Yii::app()->request->getParam('group_collect_type','');
        $group_collect_num=Yii::app()->request->getParam('group_collect_num','');
        $inductor_brand=Yii::app()->request->getParam('inductor_brand','');
        $inductor_type=Yii::app()->request->getParam('inductor_type','');
        $group_collect_install_type=Yii::app()->request->getParam('group_collect_install_type','');
        $battery_collect_type=Yii::app()->request->getParam('battery_collect_type','');
        $battery_collect_num=Yii::app()->request->getParam('battery_collect_num','');
        $humiture_type=Yii::app()->request->getParam('humiture_type','');

        if ($bms_install_date =='') {
            $bms_install_date = date('Y-m-d H:i:s');
        }
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        $model = new Site;
        $model->isNewRecord = true;
        if ($sid != 0) {
            $model->sid=$sid;
            $model->site_name=$site_name;
            $model->StationFullChineseName=$StationFullChineseName;
            $model->serial_number=$serial_number;
            $model->site_property=$site_property;
            $model->site_location=$site_location;
            $model->site_chname=$site_chname;
            $model->site_longitude=$site_longitude;
            $model->site_latitude=$site_latitude;
            $model->ipaddress=$ipaddress;
            $model->ipaddress_method=$ipaddress_method;
            $model->site_control_type=$site_control_type;
            $model->postal_code=$postal_code;
            $model->emergency_phone=$emergency_phone;
            $model->emergency_person=$emergency_person;
            $model->remark=$remark;
            $model->aid=$aid;
            $model->groups=$groups;
            $model->batteries=$batteries;
            $model->battery_status=$battery_status;
            $model->bms_install_date=$bms_install_date;
            $model->group_collect_type=$group_collect_type;
            $model->group_collect_num=$group_collect_num;
            $model->inductor_brand=$inductor_brand;
            $model->inductor_type=$inductor_type;
            $model->group_collect_install_type=$group_collect_install_type;
            $model->battery_collect_type=$battery_collect_type;
            $model->battery_collect_num=$battery_collect_num;
            $model->humiture_type=$humiture_type;
            $model->is_checked = 0 ;
            if ($model->save()) {
                $this->saveAlarm($model);
                $ret['data'] = array(
                    'id'=>$model->id,
                    'sid'=>$model->sid,
                    'site_name'=>$model->site_name,
                );
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$username,
                    'content'=>$username."新建了一个站点信息",
                    'oldvalue'=>'',
                    'newvalue'=>json_encode($model->attributes)
                );
                $this->addlog($log);
            }else{
                //var_dump($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>$this->getErrors($model->getErrors())
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'信息不能为空！'
            );
        }
        echo json_encode($ret);
        Yii::app()->end();
	}

    public function saveAlarm($site)
    {
        $i = 0 ;
        $sql = "select * from {{alarm_conf}}";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        if ($rows) {
            foreach ($rows  as $key => $value  ) {
                $model = new AlarmSiteconf;
                $model->isNewRecord = true;
                $model->sn_key = $site->serial_number;
                $model->category=$value['category'];
                $model->type=$value['type'];
                $model->content=$value['content'];
                $model->suggest=$value['suggest'];
                $model->send_email=$value['send_email'];
                $model->send_msg=$value['send_msg'];
                $model->alarm_type=$value['alarm_type'];
                $model->type_value=$value['type_value'];
                $model->operator=$value['operator'];
                $model->judge_type=$value['judge_type'];
                $model->can_ignore=$value['can_ignore'];
                $model->alarm_code = $value['alarm_code'] !='' ? $value['alarm_code'] :'none';
                $model->status = 1;
                $model->create_time = $model->update_time = time();
                if($model->save()){
                    $i++;
                }else{
                    var_dump($model->getErrors());
                    exit;
                }
            }
        }
        return $i;
    }
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
        $id = Yii::app()->request->getParam('id' ,0);
        $password = Yii::app()->request->getParam('password' ,'');
        if (!$this->checkPassword($password)) {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'密码错误！'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }
        $sid=Yii::app()->request->getParam('sid','');
        $site_name=Yii::app()->request->getParam('site_name','');
        $StationFullChineseName=Yii::app()->request->getParam('StationFullChineseName','');
        $serial_number=Yii::app()->request->getParam('serial_number','');

        $trows = Yii::app()->db->createCommand('select sid from my_site where serial_number='.$serial_number)->queryAll();
        if(!$trows){
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'不存在该站点'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }


        $site_property=Yii::app()->request->getParam('site_property','');
        $site_location=Yii::app()->request->getParam('site_location','');
        $site_chname=Yii::app()->request->getParam('site_chname','');
        $site_longitude=Yii::app()->request->getParam('site_longitude','');
        $site_latitude=Yii::app()->request->getParam('site_latitude','');
        $ipaddress=Yii::app()->request->getParam('ipaddress','');
        $ipaddress_method=Yii::app()->request->getParam('ipaddress_method','');
        $site_control_type=Yii::app()->request->getParam('site_control_type','');
        $postal_code=Yii::app()->request->getParam('postal_code','');
        $emergency_phone=Yii::app()->request->getParam('emergency_phone','');
        $emergency_person=Yii::app()->request->getParam('emergency_person','');
        $remark=Yii::app()->request->getParam('remark','');
        $aid=Yii::app()->request->getParam('area','');
        $groups=Yii::app()->request->getParam('groups','');
        $batteries=Yii::app()->request->getParam('batteries','');
        $battery_status=Yii::app()->request->getParam('battery_status','');
        $bms_install_date=Yii::app()->request->getParam('bms_install_date','');
        $group_collect_type=Yii::app()->request->getParam('group_collect_type','');
        $group_collect_num=Yii::app()->request->getParam('group_collect_num','');
        $inductor_brand=Yii::app()->request->getParam('inductor_brand','');
        $inductor_type=Yii::app()->request->getParam('inductor_type','');
        $group_collect_install_type=Yii::app()->request->getParam('group_collect_install_type','');
        $battery_collect_type=Yii::app()->request->getParam('battery_collect_type','');
        $battery_collect_num=Yii::app()->request->getParam('battery_collect_num','');
        $humiture_type=Yii::app()->request->getParam('humiture_type','');

		$model=$this->loadModel($id);
        
        if ($model) {
            $oldvalue = $model->attributes;
            $sid                       !='' && $model->sid=$sid;
            $site_name                 !='' && $model->site_name=$site_name;
            $StationFullChineseName    !='' && $model->StationFullChineseName=$StationFullChineseName;
            $serial_number             !='' && $model->serial_number=$serial_number;
            $site_property             !='' && $model->site_property=$site_property;
            $site_location             !='' && $model->site_location=$site_location;
            $site_chname               !='' && $model->site_chname=$site_chname;
            $site_longitude            !='' && $model->site_longitude=$site_longitude;
            $site_latitude             !='' && $model->site_latitude=$site_latitude;
            $ipaddress                 !='' && $model->ipaddress=$ipaddress;
            $ipaddress_method          !='' && $model->ipaddress_method=$ipaddress_method;
            $site_control_type         !='' && $model->site_control_type=$site_control_type;
            $postal_code               !='' && $model->postal_code=$postal_code;
            $emergency_phone           !='' && $model->emergency_phone=$emergency_phone;
            $emergency_person          !='' && $model->emergency_person=$emergency_person;
            $remark                    !='' && $model->remark=$remark;
            $aid                       !='' && $model->aid=$aid;
            $groups                    !='' && $model->groups=$groups;
            $batteries                 !='' && $model->batteries=$batteries;
            $battery_status            !='' && $model->battery_status=$battery_status;
            $bms_install_date          !='' && $model->bms_install_date=$bms_install_date;
            $group_collect_type        !='' && $model->group_collect_type=$group_collect_type;
            $group_collect_num         !='' && $model->group_collect_num=$group_collect_num;
            $inductor_brand            !='' && $model->inductor_brand=$inductor_brand;
            $inductor_type             !='' && $model->inductor_type=$inductor_type;
            $group_collect_install_type!='' && $model->group_collect_install_type=$group_collect_install_type;
            $battery_collect_type      !='' && $model->battery_collect_type=$battery_collect_type;
            $battery_collect_num       !='' && $model->battery_collect_num=$battery_collect_num;
            $humiture_type             !='' && $model->humiture_type=$humiture_type;

            $ret['response'] = array(
                'code'=>0,
                'msg'=>'ok'
            );
            $ret['data'] = array();
            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'sid'=>$model->sid,
                    'site_name'=>$model->site_name,
                );
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$username,
                    'content'=>$username."修改了一个站点信息",
                    'oldvalue'=>json_encode($oldvalue),
                    'newvalue'=>json_encode($model->attributes)
                );
                $this->addlog($log);
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'修改站点信息失败！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该站点信息！'
            );
        }
        echo json_encode($ret);

	}

    public function checkPassword($password)
    {
        $sql = "select * from {{program_gerneral_parameters}} where id=1";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row['password'] == $password) {
            return true;
        }
        return false;
    }
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
        $id = Yii::app()->request->getParam('id',0);
        $model = $this->loadModel($id);
        $oldvalue = $model->attributes;
        $result = $model->delete();
        $ret['response'] = array(
            'code' => 0,
            'msg' => '删除站点成功！'
        );
        $ret['data'] = array();
        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除站点失败！'
            );
        }else{
            $ret['data'] = array(
                'uid'=>$id
            );
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
            $log = array(
                'type'=>2,
                'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                'username'=>$username,
                'content'=>$username."删除了一个站点信息",
                'oldvalue'=>json_encode($oldvalue),
                'newvalue'=>''
            );
            $this->addlog($log);
        }
        echo json_encode($ret);
	}

    public function actionCheck()
    {
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $id = Yii::app()->request->getParam('id' ,0);
        $serial_number = Yii::app()->request->getParam('serial_number' ,'');
        $sql = "select * from {{station_module}} where sn_key='".$serial_number."'";
        $row = Yii::app()->bms->createCommand($sql)->queryRow();
        if ($row) {
            $model = Site::model()->findByAttributes(array('serial_number'=>$serial_number));
            if ($model) {
                $model->is_checked = 1;
                if ($model->save()) {
                    $this->createPara($row,$model);
                    $this->createGroupPara($row,$model);
                    $this->crateBatteryPara($row,$model);
                    $ret['response'] = array(
                        'code' => 0,
                        'msg' => '校验成功！'
                    );
                    $ret['data'] = array(
                        'id'=>$id,
                        '$serial_number'=>$serial_number
                    );
                }else{
                    $ret['response'] = array(
                        'code' => 0,
                        'msg' => '保存失败！'
                    );
                    $ret['data'] = array();
                }
            }else{
                $ret['response'] = array(
                    'code' => 0,
                    'msg' => '没有添加相关的站点！'
                );
                $ret['data'] = array();
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '校验失败！'
            );
            $ret['data'] = array();
        }
        echo json_encode($ret);
    }
    //生成站 组电池的默认参数
    public function createPara($row,$site)
    {
        
        $sn_key = $row['sn_key'];
        $sid = $row['sid'];
        $ret = Yii::app()->bms->createCommand('select * from tb_station_param where sn_key='.$sn_key)->queryRow();

        if (!$ret) {
            $para = array();
            $para['sn_key'] = $sn_key;
            $para['sid'] = $sid;
            $insql = Utils::buildInsertSQL($para);
            $sql = "insert into  tb_station_param ".$insql;
            Yii::app()->bms->createCommand($sql)->execute();
        }else{
            $sql = "update tb_station_param set ";
            $para = array();
            $para['sn_key'] = $sn_key;
            $para['sid'] = $sid;
            $updateSql = Utils::buildUpdateSQL($para);
            $sql = "update tb_station_param set ".$updateSql." where sn_key=".$sn_key;
            Yii::app()->bms->createCommand($sql)->execute();
        }
    }

    public function createGroupPara($row,$site)
    {
        $sn_key = $row['sn_key'];
        $sid = $row['sid'];
        $ret = Yii::app()->bms->createCommand('select * from tb_group_param where sn_key='.$sn_key)->queryRow();

        if (!$ret) {
            $para = array();
            $para['sn_key'] = $sn_key;
            //$para['sid'] = $sid;
            $insql = Utils::buildInsertSQL($para);

            $sql = "insert into  tb_group_param ".$insql;
            Yii::app()->bms->createCommand($sql)->execute();
        }else{
            $sql = "update tb_group_param set ";
            $para = array();
            $para['sn_key'] = $sn_key;
            //$para['sid'] = $sid;
            $updateSql = Utils::buildUpdateSQL($para);
            $sql = "update tb_group_param set ".$updateSql." where sn_key=".$sn_key;
            Yii::app()->bms->createCommand($sql)->execute();
        }
    }

    public function crateBatteryPara($row,$site)
    {
        $sn_key = $row['sn_key'];
        $sid = $row['sid'];
        $ret = Yii::app()->bms->createCommand('select * from tb_battery_param where sn_key='.$sn_key)->queryRow();

        if (!$ret) {
            $para = array();
            $para['sn_key'] = $sn_key;
            //$para['sid'] = $sid;
            $insql = Utils::buildInsertSQL($para);

            $sql = "insert into  tb_battery_param ".$insql;
            Yii::app()->bms->createCommand($sql)->execute();
        }else{
            $sql = "update tb_battery_param set ";
            $para = array();
            $para['sn_key'] = $sn_key;
            //$para['sid'] = $sid;
            $updateSql = Utils::buildUpdateSQL($para);
            $sql = "update tb_battery_param set ".$updateSql." where sn_key=".$sn_key;
            Yii::app()->bms->createCommand($sql)->execute();
        }
    }
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $this->setPageCount();
        //xl
        //通过sql直接选择地域进行过滤
        $sites = array();
        $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        if(!empty($sns)){
            $sql = "select * from my_site as a";
            $sql .= " where  a.serial_number in (" . implode(",", $sns) .") order by id desc ";
            $sites = Yii::app()->bms->createCommand($sql)->queryAll();
        }elseif($sns === false){
            $sites = Yii::app()->db->createCommand()
                ->select('*')
                ->from('{{site}}')
                //->limit($this->count)
                //->offset(($this->page-1)*$this->count)
                ->order('id desc')
                ->queryAll();
        }
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sites as $key=>$value){
                $sql = "select title from my_trees where id=".$value["aid"];
                $areaName = Yii::app()->bms->createCommand($sql)->queryScalar();
                $value['areaname'] = $areaName;
                $ret['data']['list'][] = $value;
            }
            
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Site('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Site']))
			$model->attributes=$_GET['Site'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Site the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Site::model()->findByAttributes(array("id"=>$id));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    public function actionSuggest()
    {
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
        {
            $sql = "select id,sid,site_name,serial_number from {{site}} where site_name like '%".$keyword."%' limit 20";
            $sites = Yii::app()->db->createCommand($sql)->queryAll();
            $data = array();
            if($sites){
                foreach ($sites as  $key=> $value ) {
                    $ret['data']['list'][$value['serial_number']] = $value['site_name'];
                }
            }
        }
        echo json_encode($ret);

    }
	/**
	 * Performs the AJAX validation.
	 * @param Site $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='site-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
