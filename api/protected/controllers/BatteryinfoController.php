<?php

class BatteryinfoController extends Controller
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
            $sql = "select bi.*,s.site_name from {{battery_info}} bi
                    left JOIN {{site}} s on bi.sid=s.serial_number where bi.id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该电池信息！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该电池信息！'
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
		$model=new BatteryInfo;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $sid=Yii::app()->request->getParam('sid','')."0000";
        $battery_factory=Yii::app()->request->getParam('battery_factory','');
        $battery_num=Yii::app()->request->getParam('battery_num','');
        $battery_date=Yii::app()->request->getParam('battery_date','');
        $battery_voltage=Yii::app()->request->getParam('battery_voltage',0.00);
        $battery_oum= Yii::app()->request->getParam('battery_oum','');
        $battery_max_current=Yii::app()->request->getParam('battery_max_current',0);
        $battery_float_up=Yii::app()->request->getParam('battery_float_up',0);
        $battery_float_dow=Yii::app()->request->getParam('battery_float_dow',0);
        $battery_discharge_down=Yii::app()->request->getParam('battery_discharge_down',0);
        $battery_scrap_date=Yii::app()->request->getParam('battery_scrap_date','');
        $battery_life=Yii::app()->request->getParam('battery_life',0);
        $battery_column_type=Yii::app()->request->getParam('battery_column_type','');
        $battery_humidity=Yii::app()->request->getParam('battery_humidity',0);
        $battery_temperature=Yii::app()->request->getParam('battery_temperature',0);
        $battery_type=Yii::app()->request->getParam('battery_type','');
        $battery_factory_phone=Yii::app()->request->getParam('battery_factory_phone','');

        $battery_float_voltage=Yii::app()->request->getParam('battery_float_voltage','');
        $battery_max_discharge_current=Yii::app()->request->getParam('battery_max_discharge_current','');
        $battery_dianrong=Yii::app()->request->getParam('battery_dianrong','');
        if ($battery_date == '') {
            $battery_date = date('Y-m-d H:i:s');
        }
        if ($battery_scrap_date =='') {
            $battery_scrap_date = date('Y-m-d');
        }
        if ($battery_voltage == '') {
            $battery_voltage = 0.00;
        }
        if ($battery_oum == '') {
            $battery_oum = 0.00;
        }
        if ($battery_max_current =='') {
            $battery_max_current = 0.00;
        }
        $battery_float_up == '' && $battery_float_up=0.00 ;
        $battery_float_dow == '' && $battery_float_dow=0.00 ;
        $battery_discharge_down == '' && $battery_discharge_down=0.00 ;
        $battery_life == '' && $battery_life=0.00 ;
        $battery_humidity == '' && $battery_humidity=0.00 ;
        $battery_temperature == '' && $battery_temperature=0.00 ;

        $battery_float_voltage == '' && $battery_float_voltage=0.00 ;
        $battery_max_discharge_current == '' && $battery_max_discharge_current=0.00 ;
        $battery_dianrong == '' && $battery_dianrong=0.00 ;
        /**
         * 检查是否是否重复，如果重复直接更新
         */
        $toUpdateSql = "select count(*) from my_battery_info where sid=".$sid;
        $hasCount = Yii::app()->db->createCommand($toUpdateSql)->queryScalar();
        if($hasCount > 0){
            // 存在重复元素 直接调用更新命令
            $this->actionUpdate();
            Yii::app()->end();
        }
        if ($sid != 0) {
            $model->sid                   =$sid                   ;
            $model->battery_factory       =$battery_factory       ;
            $model->battery_num           =$battery_num           ;
            $model->battery_date          =$battery_date          ;
            $model->battery_voltage       =$battery_voltage;
            $model->battery_oum           =$battery_oum           ;
            $model->battery_max_current   =$battery_max_current   ;
            $model->battery_float_up      =$battery_float_up      ;
            $model->battery_float_dow     =$battery_float_dow     ;
            $model->battery_discharge_down=$battery_discharge_down;
            $model->battery_scrap_date    =$battery_scrap_date    ;
            $model->battery_life          =$battery_life          ;
            $model->battery_column_type   =$battery_column_type   ;
            $model->battery_humidity      =$battery_humidity      ;
            $model->battery_temperature      = $battery_temperature;
            $model->battery_type          =$battery_type          ;
            $model->battery_factory_phone =$battery_factory_phone ;

            $model->battery_float_voltage =$battery_float_voltage ;
            $model->battery_max_discharge_current =$battery_max_discharge_current ;
            $model->battery_dianrong =$battery_dianrong ;
            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'sid'=>$model->sid,
                    'battery_num'=>$model->battery_num,
                );
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$username,
                    'content'=>$username."新建了一条电池信息",
                    'oldvalue'=>'',
                    'newvalue'=>json_encode($model->attributes)
                );
                $this->addlog($log);
            }else{
                $msg = $this->getErrors($model->getErrors());
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'新建电池数据失败！<br>'.$msg,
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
        $sid=Yii::app()->request->getParam('sid','')."0000";
        // var_dump($id);
        // return;
        if(!$id || $id == '0'){
            $id = Yii::app()->db->createCommand('select id from my_battery_info where sid='.$sid)->queryScalar();
        }
        //var_dump($id);
        //return;
        $model=$this->loadModel(intval($id));

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        $battery_factory=Yii::app()->request->getParam('battery_factory','');
        $battery_num=Yii::app()->request->getParam('battery_num','');
        $battery_date=Yii::app()->request->getParam('battery_date','');
        $battery_voltage=Yii::app()->request->getParam('battery_voltage',0);
        $battery_oum=Yii::app()->request->getParam('battery_oum','');
        $battery_max_current=Yii::app()->request->getParam('battery_max_current','');
        $battery_float_up=Yii::app()->request->getParam('battery_float_up','');
        $battery_float_dow=Yii::app()->request->getParam('battery_float_dow','');
        $battery_discharge_down=Yii::app()->request->getParam('battery_discharge_down','');
        $battery_scrap_date=Yii::app()->request->getParam('battery_scrap_date','');
        $battery_life=Yii::app()->request->getParam('battery_life','');
        $battery_column_type=Yii::app()->request->getParam('battery_column_type','');
        $battery_humidity=Yii::app()->request->getParam('battery_humidity','');
        $battery_temperature=Yii::app()->request->getParam('battery_temperature','');
        $battery_type=Yii::app()->request->getParam('battery_type','');
        $battery_factory_phone=Yii::app()->request->getParam('battery_factory_phone','');

        $battery_float_voltage=Yii::app()->request->getParam('battery_float_voltage','');
        $battery_max_discharge_current=Yii::app()->request->getParam('battery_max_discharge_current','');
        $battery_dianrong=Yii::app()->request->getParam('battery_dianrong','');

        if ($sid != 0) {
            $oldvalue = $model->attributes;
           $sid                    != '' &&  $model->sid                   =$sid                   ;
           $battery_factory        != '' &&  $model->battery_factory       =$battery_factory       ;
           $battery_num            != '' &&  $model->battery_num           =$battery_num           ;
           $battery_date           != '' &&  $model->battery_date          =$battery_date          ;
           $battery_voltage        != '' &&  $model->battery_voltage       =$battery_voltage       ;
           $battery_oum            != '' &&  $model->battery_oum           =$battery_oum           ;
           $battery_max_current    != '' &&  $model->battery_max_current   =$battery_max_current   ;
           $battery_float_up       != '' &&  $model->battery_float_up      =$battery_float_up      ;
           $battery_float_dow      != '' &&  $model->battery_float_dow     =$battery_float_dow     ;
           $battery_discharge_down != '' &&  $model->battery_discharge_down=$battery_discharge_down;
           $battery_scrap_date     != '' &&  $model->battery_scrap_date    =$battery_scrap_date    ;
           $battery_life           != '' &&  $model->battery_life          =$battery_life          ;
           $battery_column_type    != '' &&  $model->battery_column_type   =$battery_column_type   ;
           $battery_humidity       != '' &&  $model->battery_humidity      =$battery_humidity      ;
           $battery_temperature       != '' &&  $model->battery_temperature      = $battery_temperature;
           $battery_type           != '' &&  $model->battery_type          =$battery_type          ;
           $battery_factory_phone  != '' &&  $model->battery_factory_phone =$battery_factory_phone ;

           $battery_float_voltage  != '' &&  $model->battery_float_voltage = $battery_float_voltage;
           $battery_max_discharge_current != ''&&$model->battery_max_discharge_current =$battery_max_discharge_current;
           $battery_dianrong  != '' &&  $model->battery_dianrong =$battery_dianrong ;

            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'sid'=>$model->sid,
                    'battery_num'=>$model->battery_num,
                );
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$username,
                    'content'=>$username."新建了一条电池信息",
                    'oldvalue'=>json_encode($oldvalue),
                    'newvalue'=>json_encode($model->attributes)
                );
                $this->addlog($log);
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'新建电池数据失败！'
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
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
        $id = Yii::app()->request->getParam('id' ,0);
        $ret['response'] = array(
            'code' => 0,
            'msg' => '删除电池成功!'
        );
        $ret['data'] = array();
        $model = $this->loadModel($id);
        $oldvalue = $model->attributes;
        $result = $model->delete();

        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除电池失败！'
            );
        }else{
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
            $log = array(
                'type'=>2,
                'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                'username'=>$username,
                'content'=>$username."删除了一条电池信息",
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
             $sql = "select b.* from my_battery_info b, my_site a ";
             $sql .= " where 1=1 ";
             $sql .= " and b.sid = a.serial_number ";
             $sql .= "and a.serial_number in (" . implode(",", $sns) .") ";
         }
         elseif($sns === false){
            $sql = "SELECT b . * , s.site_name, s.sid
                FROM  {{battery_info}} AS b
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
                'msg' => '暂无电池数据！'
            );
        }

        echo json_encode($ret);
	}



	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BatteryInfo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BatteryInfo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BatteryInfo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='battery-info-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
