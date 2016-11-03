<?php

class UpsinfoController extends Controller
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
    public function checkPassword($password)
    {
        $sql = "select * from {{program_gerneral_parameters}} where id=1";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row['password'] == $password) {
            return true;
        }
        return false;
    }
	public function actionView()
	{
        $id = Yii::app()->request->getParam('id',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select ui.*,s.site_name,s.sid as usid from {{ups_info}} ui
                    LEFT  JOIN  {{site}} s on ui.sid = s.serial_number where ui.id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该UPS！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该UPS！'
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
		$model=new UpsInfo;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $password = Yii::app()->request->getParam('password' ,'');
        if (!$this->checkPassword($password)) {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'密码错误！'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }
        $ret['data'] = array();

        $sid=Yii::app()->request->getParam('sid',0)."0000";
        /**
         * 检查是否是否重复，如果重复直接更新
         */
        $toUpdateSql = "select count(*) from my_ups_info where sid=".$sid;
        $hasCount = Yii::app()->db->createCommand($toUpdateSql)->queryScalar();
        if($hasCount > 0){
            // 存在重复元素 直接调用更新命令
            $this->actionUpdate();
            Yii::app()->end();
        }


        $ups_factory=Yii::app()->request->getParam('ups_factory','');
        $ups_type=Yii::app()->request->getParam('ups_type','');
        $ups_create_date=Yii::app()->request->getParam('ups_create_date','');
        $ups_install_date=Yii::app()->request->getParam('ups_install_date','');
        $ups_power=Yii::app()->request->getParam('ups_power','');
        $redundancy_num=Yii::app()->request->getParam('redundancy_num','');
        $floting_charge=Yii::app()->request->getParam('floting_charge','');
        $ups_vdc=Yii::app()->request->getParam('ups_vdc','');
        $ups_reserve_hour=Yii::app()->request->getParam('ups_reserve_hour','');
        $ups_charge_mode=Yii::app()->request->getParam('ups_charge_mode','');
        $ups_max_charge=Yii::app()->request->getParam('ups_max_charge','');
        $ups_max_discharge=Yii::app()->request->getParam('ups_max_discharge','');
        $ups_period_days=Yii::app()->request->getParam('ups_period_days','');
        $ups_discharge_time=Yii::app()->request->getParam('ups_discharge_time','');
        $ups_discharge_capacity=Yii::app()->request->getParam('ups_discharge_capacity','');
        $ups_maintain_date=Yii::app()->request->getParam('ups_maintain_date','');
        $ups_vender_phone=Yii::app()->request->getParam('ups_vender_phone','');
        $ups_vender=Yii::app()->request->getParam('ups_vender','');
        $ups_service=Yii::app()->request->getParam('ups_service','');
        $ups_service_phone=Yii::app()->request->getParam('ups_service_phone','');

        $usid=Yii::app()->request->getParam('usid','');
        $ups_power_in=Yii::app()->request->getParam('ups_power_in','');
        $ups_power_out=Yii::app()->request->getParam('ups_power_out','');
        $ups_battery_vol=Yii::app()->request->getParam('ups_battery_vol','');
        $ups_battery_current=Yii::app()->request->getParam('ups_battery_current','');
        $ac_protect=Yii::app()->request->getParam('ac_protect','');
        $dc_protect=Yii::app()->request->getParam('dc_protect','');
        $on_net=Yii::app()->request->getParam('on_net','');
        $alarm_content=Yii::app()->request->getParam('alarm_content','');
        $discharge_protect=Yii::app()->request->getParam('discharge_protect','');

        $remark=Yii::app()->request->getParam('remark','');

        $ups_power == '' && $ups_power=0.00 ;
        $floting_charge == '' && $floting_charge=0.00 ;
        $ups_vdc == '' && $ups_vdc=0.00 ;
        $ups_max_charge == '' && $ups_max_charge=0.00 ;
        $ups_max_discharge == '' && $ups_max_discharge=0.00 ;
        $ups_discharge_capacity == '' && $ups_discharge_capacity=0.00 ;
        if ($ups_create_date == '') {
            $ups_create_date = date("Y-m-d H:i:s");
        }
        if ($ups_install_date == '') {
            $ups_install_date = date("Y-m-d H:i:s");
        }
        if ($ups_maintain_date == '') {
            $ups_maintain_date = date("Y-m-d");
        }
        if ($sid != 0) {
            $model->sid                   = $sid;
            $model->ups_factory           = $ups_factory;
            $model->ups_type              = $ups_type;
            $model->ups_create_date       = $ups_create_date;
            $model->ups_install_date      = $ups_install_date;
            $model->ups_power             = $ups_power;
            $model->redundancy_num        = $redundancy_num;
            $model->floting_charge        = $floting_charge;
            $model->ups_vdc               = $ups_vdc;
            $model->ups_reserve_hour      = $ups_reserve_hour;
            $model->ups_charge_mode       = $ups_charge_mode;
            $model->ups_max_charge        = $ups_max_charge;
            $model->ups_max_discharge     = $ups_max_discharge;
            $model->ups_period_days       = $ups_period_days;
            $model->ups_discharge_time    = $ups_discharge_time;
            $model->ups_discharge_capacity= $ups_discharge_capacity;
            $model->ups_maintain_date     = $ups_maintain_date;
            $model->ups_vender_phone      = $ups_vender_phone;
            $model->ups_vender            = $ups_vender;
            $model->ups_service           = $ups_service;
            $model->ups_service_phone     = $ups_service_phone;

            $model->usid     = $usid;
            $model->ups_power_in     = $ups_power_in;
            $model->ups_power_out     = $ups_power_out;
            $model->ups_battery_vol     = $ups_battery_vol;
            $model->ups_battery_current     = $ups_battery_current;
            $model->ac_protect     = $ac_protect;
            $model->dc_protect     = $dc_protect;
            $model->on_net     = $on_net;
            $model->alarm_content     = $alarm_content;
            $model->discharge_protect     = $discharge_protect;
            $model->remark     = $remark;

            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'sid'=>$model->sid,
                    'ups_type'=>$model->ups_type,
                );
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$username,
                    'content'=>$username."添加了一条UPS信息",
                    'oldvalue'=>'',
                    'newvalue'=>json_encode($model->attributes)
                );
                $this->addlog($log);
            }else{
                $msg = $this->getErrors($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'新建UPS数据失败！<br>'.$msg,
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
        $password = Yii::app()->request->getParam('password' ,'');
        if (!$this->checkPassword($password)) {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'密码错误！'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }
        $id = Yii::app()->request->getParam('id' ,0);
        $sid = Yii::app()->request->getParam('sid', 0)."0000";
        if(!$id || $id==0){
            $id = Yii::app()->db->createCommand('select id from my_ups_info where sid='.$sid)->queryScalar();
        }
        $model=$this->loadModel($id);

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        if ($model) {
            $sid = Yii::app()->request->getParam('sid', 0);
            $ups_factory = Yii::app()->request->getParam('ups_factory', '');
            $ups_type = Yii::app()->request->getParam('ups_type', '');
            $ups_create_date = Yii::app()->request->getParam('ups_create_date', '');
            $ups_install_date = Yii::app()->request->getParam('ups_install_date', '');
            $ups_power = Yii::app()->request->getParam('ups_power', '');
            $redundancy_num = Yii::app()->request->getParam('redundancy_num', '');
            $floting_charge = Yii::app()->request->getParam('floting_charge', '');
            $ups_vdc = Yii::app()->request->getParam('ups_vdc', '');
            $ups_reserve_hour = Yii::app()->request->getParam('ups_reserve_hour', '');
            $ups_charge_mode = Yii::app()->request->getParam('ups_charge_mode', '');
            $ups_max_charge = Yii::app()->request->getParam('ups_max_charge', '');
            $ups_max_discharge = Yii::app()->request->getParam('ups_max_discharge', '');
            $ups_period_days = Yii::app()->request->getParam('ups_period_days', '');
            $ups_discharge_time = Yii::app()->request->getParam('ups_discharge_time', '');
            $ups_discharge_capacity = Yii::app()->request->getParam('ups_discharge_capacity', '');
            $ups_maintain_date = Yii::app()->request->getParam('ups_maintain_date', '');
            $ups_vender_phone = Yii::app()->request->getParam('ups_vender_phone', '');
            $ups_vender = Yii::app()->request->getParam('ups_vender', '');
            $ups_service = Yii::app()->request->getParam('ups_service', '');
            $ups_service_phone = Yii::app()->request->getParam('ups_service_phone', '');

            $usid=Yii::app()->request->getParam('usid','');
            $ups_power_in=Yii::app()->request->getParam('ups_power_in','');
            $ups_power_out=Yii::app()->request->getParam('ups_power_out','');
            $ups_battery_vol=Yii::app()->request->getParam('ups_battery_vol','');
            $ups_battery_current=Yii::app()->request->getParam('ups_battery_current','');
            $ac_protect=Yii::app()->request->getParam('ac_protect','');
            $dc_protect=Yii::app()->request->getParam('dc_protect','');
            $on_net=Yii::app()->request->getParam('on_net','');
            $alarm_content=Yii::app()->request->getParam('alarm_content','');
            $discharge_protect=Yii::app()->request->getParam('discharge_protect','');
            $remark=Yii::app()->request->getParam('remark','');


            if ($sid != 0){
                $oldvalue = $model->attributes;
                $sid                    !=0 && $model->sid                   = $sid;
                $ups_factory            !='' && $model->ups_factory           = $ups_factory;
                $ups_type               !='' && $model->ups_type              = $ups_type;
                $ups_create_date        !='' && $model->ups_create_date       = $ups_create_date;
                $ups_install_date       !='' && $model->ups_install_date      = $ups_install_date;
                $ups_power              !='' && $model->ups_power             = $ups_power;
                $redundancy_num         !='' && $model->redundancy_num        = $redundancy_num;
                $floting_charge         !='' && $model->floting_charge        = $floting_charge;
                $ups_vdc                !='' && $model->ups_vdc               = $ups_vdc;
                $ups_reserve_hour       !='' && $model->ups_reserve_hour      = $ups_reserve_hour;
                $ups_charge_mode        !='' && $model->ups_charge_mode       = $ups_charge_mode;
                $ups_max_charge         !='' && $model->ups_max_charge        = $ups_max_charge;
                $ups_max_discharge      !='' && $model->ups_max_discharge     = $ups_max_discharge;
                $ups_period_days        !='' && $model->ups_period_days       = $ups_period_days;
                $ups_discharge_time     !='' && $model->ups_discharge_time    = $ups_discharge_time;
                $ups_discharge_capacity !='' && $model->ups_discharge_capacity= $ups_discharge_capacity;
                $ups_maintain_date      !='' && $model->ups_maintain_date     = $ups_maintain_date;
                $ups_vender_phone       !='' && $model->ups_vender_phone      = $ups_vender_phone;
                $ups_vender             !='' && $model->ups_vender            = $ups_vender;
                $ups_service            !='' && $model->ups_service           = $ups_service;
                $ups_service_phone      !='' && $model->ups_service_phone     = $ups_service_phone;

                $usid  !='' && $model->usid     = $usid;
                $ups_power_in  !='' && $model->ups_power_in     = $ups_power_in;
                $ups_power_out  !='' && $model->ups_power_out     = $ups_power_out;
                $ups_battery_vol  !='' && $model->ups_battery_vol     = $ups_battery_vol;
                $ups_battery_current  !='' && $model->ups_battery_current     = $ups_battery_current;
                $ac_protect  !='' && $model->ac_protect     = $ac_protect;
                $dc_protect  !='' && $model->dc_protect     = $dc_protect;
                $on_net  !='' && $model->on_net     = $on_net;
                $alarm_content  !='' && $model->alarm_content     = $alarm_content;
                $discharge_protect  !='' && $model->discharge_protect     = $discharge_protect;
                $remark  !='' && $model->remark     = $remark;
                if ($model->save()) {
                    $ret['data'] = array(
                        'id' => $model->id,
                        'sid' => $model->sid,
                        'ups_type' => $model->ups_type,
                    );
                    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                    $log = array(
                        'type'=>2,
                        'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                        'username'=>$username,
                        'content'=>$username."修改了一条UPS信息",
                        'oldvalue'=>json_encode($oldvalue),
                        'newvalue'=>json_encode($model->attributes)
                    );
                    $this->addlog($log);
                } else {
                    $ret['response'] = array(
                        'code' => -1,
                        'msg' => '新建电池数据失败！'
                    );
                }
            } else {
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => '站点不能为空！'
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有该ups信息！'
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
            'msg' => '删除UPS成功!'
        );
        $ret['data'] = array();
        $model = $this->loadModel($id);
        $oldvalue = $model->attributes;
        $result = $model->delete();


        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除UPS失败！'
            );
        }else{
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
            $log = array(
                'type'=>2,
                'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                'username'=>$username,
                'content'=>$username."删除了一条UPS信息",
                'oldvalue'=>json_encode($oldvalue),
                'newvalue'=>''
            );
            $this->addlog($log);
        }

        echo json_encode($ret);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $this->setPageCount();
        $offset = ($this->page-1)*$this->count;

         //xl
         //通过sql直接选择地域进行过滤
         $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
         if(!empty($sns)){
             // $sql = "select b.* from my_battery_info b, my_site a ";
             // $sql .= " where 1=1 ";
             // $sql .= " and b.sid = a.serial_number ";
             // $sql .= "and a.serial_number in (" . implode(",", $sns) .") ";

             $sql = "SELECT b . * , s.site_name, s.sid
                FROM  {{ups_info}} AS b
                LEFT JOIN {{site}} AS s ON b.sid = s.serial_number
                where s.serial_number in (" . implode(",", $sns) .") ";

         }
         elseif($sns === false){

            $sql = "SELECT b . * , s.site_name, s.sid
                FROM  {{ups_info}} AS b
                LEFT JOIN {{site}} AS s ON b.sid = s.serial_number";
         }
        $ups = Yii::app()->db->createCommand($sql)->queryAll();
        //$ups = Yii::app()->db->createCommand()
        //    ->select('s.site_name,bi.*')
        //    ->from('{{battery_info}} bi')
        //    ->leftJoin('{{site}} s','bi.sid = s.id')
        //    ->limit($this->count)
        //    ->offset(($this->page-1)*$this->count)
        //    ->order('bi.id desc')
        //    ->queryAll();
        //echo json_encode($ups);
        //exit;
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
                'msg' => '暂无UPS数据！'
            );
        }

        echo json_encode($ret);


        // $this->setPageCount();
        // $ups = Yii::app()->db->createCommand()
        //     ->select('ui.*,s.site_name,s.sid as truesid')
        //     ->from('{{ups_info}} ui')
        //     ->leftJoin('{{site}} s','ui.sid = s.serial_number')
        //     //->limit($this->count)
        //     //->offset(($this->page-1)*$this->count)
        //     ->order('ui.id desc')
        //     ->queryAll();
        // $ret['response'] = array(
        //     'code' => 0,
        //     'msg' => 'ok'
        // );
        // $ret['data'] = array();

        // if ($ups) {
        //     $ret['data']['page'] = $this->page;
        //     $ret['data']['pageSize'] = $this->count;

        //     foreach($ups as $key=>$value){
        //         $ret['data']['list'][] = $value;
        //     }

        // }else{
        //     $ret['response'] = array(
        //         'code' => -1,
        //         'msg' => '暂无UPS数据！'
        //     );
        // }

        // echo json_encode($ret);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UpsInfo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UpsInfo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UpsInfo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ups-info-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
