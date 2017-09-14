<?php

class GroupparaController extends Controller
{
    public static function getStationIds($zero = '0000'){
        $id = Yii::app()->request->getParam('id',0);
        if(!$id){
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点数据！'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }
        $arr = explode(',',$id);
        $temp = array();

        foreach ($arr as $key => $value) {
            $temp[] = $value.$zero;
        }

        $id =  implode(',',$temp);
        return $temp;
    }

	public function actionIndex()
	{
        $this->setPageCount();
        // $id = Yii::app()->request->getParam('id',0);
        $temp = self::getStationIds();
        $site = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{site}}')
            ->queryAll();
        if($temp){
            $site = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{site}}')
            ->where('serial_number in ('.implode(",",$temp).')')
            ->queryAll();
            
        }
        $data = array();
        if ($site) {
            foreach ($site as $key => $value) {
                $data[floor($value['serial_number']/10000)] = $value;
            }
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        
        $batteryparm = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{group_param}}')
            ->order('sn_key asc')
            // ->order('gid desc')
            ->queryAll();
        if($temp){
            $batteryparm = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{group_param}}')
            ->where('sn_key in ('.implode(",",$temp).')')
            ->order('sn_key asc')
            ->queryAll();
        }

        if ($batteryparm) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($batteryparm as $key=>$value){
                if (isset($data[floor($value['sn_key']/10000)])) {
                    $value['site_name'] = $data[floor($value['sn_key']/10000)]['site_name'];
                    $value['sid'] = $data[floor($value['sn_key']/10000)]['sid'];
                }else{
                    $value['site_name'] = '未添加站点';
                    $value['sid'] = "";
                }
                $ret['data']['list'][] = $value;
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无组参数数据！'
            );
        }

        echo json_encode($ret);
	}
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView()
    {
        $id = Yii::app()->request->getParam('sn_key',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select * from {{group_param}}
                    where sn_key=" . $id;
            $row = Yii::app()->bms->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该组参数！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该组参数！'
            );
        }
        echo json_encode($ret);

    }
    public function actionCreate()
    {
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $group_sn_key=Yii::app()->request->getParam('group_sn_key','');
        $sid=Yii::app()->request->getParam('sid','');
        $gid = Yii::app()->request->getParam('gid' ,0);
        $K_Battery_Incide=Yii::app()->request->getParam('K_Battery_Incide','');
        $HaveCurrentSensor=Yii::app()->request->getParam('HaveCurrentSensor','');
        $StationCurrentSensorSpan=Yii::app()->request->getParam('StationCurrentSensorSpan','');
        $StationCurrentSensorZeroADCode=Yii::app()->request->getParam('StationCurrentSensorZeroADCode','');
        $OSC=Yii::app()->request->getParam('OSC','');
        $DisbytegeCurrentLimit=Yii::app()->request->getParam('DisbytegeCurrentLimit','');
        $bytegeCurrentLimit=Yii::app()->request->getParam('bytegeCurrentLimit','');
        $TemperatureHighLimit=Yii::app()->request->getParam('TemperatureHighLimit','');
        $TemperatureLowLimit=Yii::app()->request->getParam('TemperatureLowLimit','');
        $HumiH=Yii::app()->request->getParam('HumiH','');
        $HumiL=Yii::app()->request->getParam('HumiL','');
        $TemperatureAdjust=Yii::app()->request->getParam('TemperatureAdjust','');
        $HumiAdjust=Yii::app()->request->getParam('HumiAdjust','');

        if ($group_sn_key) {
            $row = array();
            $group_sn_key != '' && $row['group_sn_key']=$group_sn_key;
            $sid != '' && $row['sid']=$sid;
            $gid != '' && $row['gid']=$gid;
            $K_Battery_Incide != '' && $row['K_Battery_Incide']=$K_Battery_Incide;
            $HaveCurrentSensor != '' && $row['HaveCurrentSensor']=$HaveCurrentSensor;
            $StationCurrentSensorSpan != '' && $row['StationCurrentSensorSpan']=$StationCurrentSensorSpan;
            $StationCurrentSensorZeroADCode != '' && $row['StationCurrentSensorZeroADCode']=$StationCurrentSensorZeroADCode;
            $OSC != '' && $row['OSC']=$OSC;
            $DisbytegeCurrentLimit != '' && $row['DisbytegeCurrentLimit']=$DisbytegeCurrentLimit;
            $bytegeCurrentLimit != '' && $row['bytegeCurrentLimit']=$bytegeCurrentLimit;
            $TemperatureHighLimit != '' && $row['TemperatureHighLimit']=$TemperatureHighLimit;
            $TemperatureLowLimit != '' && $row['TemperatureLowLimit']=$TemperatureLowLimit;
            $HumiH != '' && $row['HumiH']=$HumiH;
            $HumiL != '' && $row['HumiL']=$HumiL;
            $TemperatureAdjust != '' && $row['TemperatureAdjust']=$TemperatureAdjust;
            $HumiAdjust != '' && $row['HumiAdjust']=$HumiAdjust;

            $insql = Utils::buildInsertSQL($row);
            $sql = "update  set {{group_parameter}} ".$insql ." where group_sn_key=".$group_sn_key;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'group_sn_key'=>$group_sn_key,
                    'sid' =>$sid,
                    'gid'=>$gid,
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有组参数数据！'
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


        $sn_key = Yii::app()->request->getParam('sn_key' ,0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $GroBatNum=Yii::app()->request->getParam('GroBatNum','');
        $CurRange=Yii::app()->request->getParam('CurRange','');
        $KI=Yii::app()->request->getParam('KI','');
        $ZeroCurADC=Yii::app()->request->getParam('ZeroCurADC','');
        $DisChaLim_R=Yii::app()->request->getParam('DisChaLim_R','');
        $DisChaLim_O=Yii::app()->request->getParam('DisChaLim_O','');
        $DisChaLim_Y=Yii::app()->request->getParam('DisChaLim_Y','');
        $ChaLim_R=Yii::app()->request->getParam('ChaLim_R','');
        $ChaLim_O=Yii::app()->request->getParam('ChaLim_O','');
        $ChaLim_Y=Yii::app()->request->getParam('ChaLim_Y','');
        $MaxTem_R=Yii::app()->request->getParam('MaxTem_R','');
        $MaxTem_O=Yii::app()->request->getParam('MaxTem_O','');
        $MaxTem_Y=Yii::app()->request->getParam('MaxTem_Y','');
        $MinTem_R=Yii::app()->request->getParam('MinTem_R','');
        $MinTem_O=Yii::app()->request->getParam('MinTem_O','');
        $MinTem_Y=Yii::app()->request->getParam('MinTem_Y','');

        $MaxHumi_R=Yii::app()->request->getParam('MaxHumi_R','');
        $MaxHumi_O=Yii::app()->request->getParam('MaxHumi_O','');
        $MaxHumi_Y=Yii::app()->request->getParam('MaxHumi_Y','');
        $MinHumi_R=Yii::app()->request->getParam('MinHumi_R','');
        $MinHumi_O=Yii::app()->request->getParam('MinHumi_O','');
        $MinHumi_Y=Yii::app()->request->getParam('MinHumi_Y','');

        $ChaCriterion=Yii::app()->request->getParam('ChaCriterion','');

        if ($sn_key) {
            $row = array();
            $GroBatNum != '' && $row['GroBatNum']=$GroBatNum;
            $CurRange != '' && $row['CurRange']=$CurRange;
            $KI != '' && $row['KI']=$KI;
            $ZeroCurADC != '' && $row['ZeroCurADC']=$ZeroCurADC;
            $DisChaLim_R != '' && $row['DisChaLim_R']=$DisChaLim_R;
            $DisChaLim_O != '' && $row['DisChaLim_O']=$DisChaLim_O;
            $DisChaLim_Y != '' && $row['DisChaLim_Y']=$DisChaLim_Y;
            $ChaLim_R != '' && $row['ChaLim_R']=$ChaLim_R;
            $ChaLim_O != '' && $row['ChaLim_O']=$ChaLim_O;
            $ChaLim_Y != '' && $row['ChaLim_Y']=$ChaLim_Y;
            $MaxTem_R != '' && $row['MaxTem_R']=$MaxTem_R;
            $MaxTem_O != '' && $row['MaxTem_O']=$MaxTem_O;
            $MaxTem_Y != '' && $row['MaxTem_Y']=$MaxTem_Y;
            $MinTem_R != '' && $row['MinTem_R']=$MinTem_R;
            $MinTem_O != '' && $row['MinTem_O']=$MinTem_O;
            $MinTem_Y != '' && $row['MinTem_Y']=$MinTem_Y;

            $MaxHumi_R != '' && $row['MaxHumi_R']=$MaxHumi_R;
            $MaxHumi_O != '' && $row['MaxHumi_O']=$MaxHumi_O;
            $MaxHumi_Y != '' && $row['MaxHumi_Y']=$MaxHumi_Y;
            $MinHumi_R != '' && $row['MinHumi_R']=$MinHumi_R;
            $MinHumi_O != '' && $row['MinHumi_O']=$MinHumi_O;
            $MinHumi_Y != '' && $row['MinHumi_Y']=$MinHumi_Y;


            $ChaCriterion != '' && $row['ChaCriterion']=$ChaCriterion;

            $upsql = Utils::buildUpdateSQL($row);
            $sql = "update  {{group_param}} set ".$upsql." where sn_key=".$sn_key;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'sn_key' =>$sn_key
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有组参数数据！'
            );
        }
        echo json_encode($ret);
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