<?php

class StationparaController extends Controller
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
        // $id = Yii::app()->request->getParam('id',0);
        // $temp = self::getStationIds();

        $this->setPageCount();
        // var_dump(implode(",",$temp));
        $site = Yii::app()->db->createCommand()
            ->select('*')
            ->from('my_site')
            ->queryAll();
        // if($temp){
        //     $site = Yii::app()->db->createCommand()
        //         ->select('*')
        //         ->from('{{site}}')
        //         //->where('serial_number in ('.implode(",",$temp).')')
        //         ->queryAll();
        // }

        $data = array();
        if ($site) {
            foreach ($site as $key => $value) {
                $data[$value['serial_number']] = $value;
            }
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $batteryparm = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{station_param}}')
            ->queryAll();
        // if($temp){
        //     $batteryparm = Yii::app()->bms->createCommand()
        //         ->select('*')
        //         ->from('{{station_param}}')
        //         //->where('sn_key in ('.implode(",",$temp).')')
        //         ->order('sid desc')
        //         ->queryAll();
        // }
        if ($batteryparm) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($batteryparm as $key=>$value){
                if (isset($data[$value['sn_key']])) {
                    $value['site_name'] = $data[$value['sn_key']]['site_name'];
                }else{
                    $value['site_name'] = '未添加站点';
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
        $id = Yii::app()->request->getParam('sid',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select * from {{station_param}}
                    where sn_key=" . $id;
            $row = Yii::app()->bms->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该站参数！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该站参数！'
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
        $sid = Yii::app()->request->getParam('sid' ,0);
        $station_sn_key=Yii::app()->request->getParam('station_sn_key','');
        $MAC_address=Yii::app()->request->getParam('MAC_address','');
        $N_Groups_Incide=Yii::app()->request->getParam('N_Groups_Incide','');
        $Time_interval_Rin=Yii::app()->request->getParam('Time_interval_Rin','');
        $Time_interval_U=Yii::app()->request->getParam('Time_interval_U','');
        $U_abnormal_limit=Yii::app()->request->getParam('U_abnormal_limit','');
        $T_abnormal_limit=Yii::app()->request->getParam('T_abnormal_limit','');
        $Rin_abnormal_limit=Yii::app()->request->getParam('Rin_abnormal_limit','');
        $T_upper_limit=Yii::app()->request->getParam('T_upper_limit','');
        $T_lower_limit=Yii::app()->request->getParam('T_lower_limit','');
        $Humi_upper_limit=Yii::app()->request->getParam('Humi_upper_limit','');
        $Humi_lower_limit=Yii::app()->request->getParam('Humi_lower_limit','');
        $Group_I_criterion=Yii::app()->request->getParam('Group_I_criterion','');
        $bytegeStatus_U_upper=Yii::app()->request->getParam('bytegeStatus_U_upper','');
        $bytegeStatus_U_lower=Yii::app()->request->getParam('bytegeStatus_U_lower','');
        $FloatingbytegeStatus_U_upper=Yii::app()->request->getParam('FloatingbytegeStatus_U_upper','');
        $FloatingbytegeStatus_U_lower=Yii::app()->request->getParam('FloatingbytegeStatus_U_lower','');
        $DisbytegeStatus_U_upper=Yii::app()->request->getParam('DisbytegeStatus_U_upper','');
        $DisbytegeStatus_U_lower=Yii::app()->request->getParam('DisbytegeStatus_U_lower','');
        $N_Groups_Incide_Station=Yii::app()->request->getParam('N_Groups_Incide_Station','');
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
        if ($sid) {
            $row = array();
            $station_sn_key !='' && $row['station_sn_key']=$station_sn_key;
            $MAC_address !='' && $row['MAC_address']=$MAC_address;
            $sid !='' && $row['sid']= $sid;
            $N_Groups_Incide !='' && $row['N_Groups_Incide']=$N_Groups_Incide;
            $Time_interval_Rin !='' && $row['Time_interval_Rin']=$Time_interval_Rin;
            $Time_interval_U !='' && $row['Time_interval_U']=$Time_interval_U;
            $U_abnormal_limit !='' && $row['U_abnormal_limit']=$U_abnormal_limit;
            $T_abnormal_limit !='' && $row['T_abnormal_limit']=$T_abnormal_limit;
            $Rin_abnormal_limit !='' && $row['Rin_abnormal_limit']=$Rin_abnormal_limit;
            $T_upper_limit !='' && $row['T_upper_limit']=$T_upper_limit;
            $T_lower_limit !='' && $row['T_lower_limit']=$T_lower_limit;
            $Humi_upper_limit !='' && $row['Humi_upper_limit']=$Humi_upper_limit;
            $Humi_lower_limit !='' && $row['Humi_lower_limit']=$Humi_lower_limit;
            $Group_I_criterion !='' && $row['Group_I_criterion']=$Group_I_criterion;
            $bytegeStatus_U_upper !='' && $row['bytegeStatus_U_upper']=$bytegeStatus_U_upper;
            $bytegeStatus_U_lower !='' && $row['bytegeStatus_U_lower']=$bytegeStatus_U_lower;
            $FloatingbytegeStatus_U_upper !='' && $row['FloatingbytegeStatus_U_upper']=$FloatingbytegeStatus_U_upper;
            $FloatingbytegeStatus_U_lower !='' && $row['FloatingbytegeStatus_U_lower']=$FloatingbytegeStatus_U_lower;
            $DisbytegeStatus_U_upper !='' && $row['DisbytegeStatus_U_upper']=$DisbytegeStatus_U_upper;
            $DisbytegeStatus_U_lower !='' && $row['DisbytegeStatus_U_lower']=$DisbytegeStatus_U_lower;
            $N_Groups_Incide_Station !='' && $row['N_Groups_Incide_Station']=$N_Groups_Incide_Station;
            $HaveCurrentSensor !='' && $row['HaveCurrentSensor']=$HaveCurrentSensor;
            $StationCurrentSensorSpan !='' && $row['StationCurrentSensorSpan']=$StationCurrentSensorSpan;
            $StationCurrentSensorZeroADCode !='' && $row['StationCurrentSensorZeroADCode']=$StationCurrentSensorZeroADCode;
            $OSC !='' && $row['OSC']=$OSC;
            $DisbytegeCurrentLimit !='' && $row['DisbytegeCurrentLimit']=$DisbytegeCurrentLimit;
            $bytegeCurrentLimit !='' && $row['bytegeCurrentLimit']=$bytegeCurrentLimit;
            $TemperatureHighLimit !='' && $row['TemperatureHighLimit']=$TemperatureHighLimit;
            $TemperatureLowLimit !='' && $row['TemperatureLowLimit']=$TemperatureLowLimit;
            $HumiH !='' && $row['HumiH']=$HumiH;
            $HumiL !='' && $row['HumiL']=$HumiL;
            $TemperatureAdjust !='' && $row['TemperatureAdjust']=$TemperatureAdjust;
            $HumiAdjust !='' && $row['HumiAdjust']=$HumiAdjust;

            $insql = Utils::buildInsertSQL($row);
            $sql = "INSERT INTO {{station_parameter}} ".$insql;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'sid' =>$sid,
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有站参数数据！'
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

        $sid = Yii::app()->request->getParam('sid' ,0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        $sn_key=Yii::app()->request->getParam('sn_key','');

        $Groups=Yii::app()->request->getParam('Groups','');
        $GroBats=Yii::app()->request->getParam('GroBats','');
        $Time_MR=Yii::app()->request->getParam('Time_MR','');
        $Time_MV=Yii::app()->request->getParam('Time_MV','');
        $MaxTem_R=Yii::app()->request->getParam('MaxTem_R','');
        $MaxTem_O=Yii::app()->request->getParam('MaxTem_O','');
        $MaxTem_Y=Yii::app()->request->getParam('MaxTem_Y','');
        $MinTem_R=Yii::app()->request->getParam('MinTem_R','');
        $MinTem_O=Yii::app()->request->getParam('MinTem_O','');
        $MinTem_Y=Yii::app()->request->getParam('MinTem_Y','');
        $MaxHum_R=Yii::app()->request->getParam('MaxHum_R','');
        $MaxHum_O=Yii::app()->request->getParam('MaxHum_O','');
        $MaxHum_Y=Yii::app()->request->getParam('MaxHum_Y','');
        $MinHum_R=Yii::app()->request->getParam('MinHum_R','');
        $MinHum_O=Yii::app()->request->getParam('MinHum_O','');
        $MinHum_Y=Yii::app()->request->getParam('MinHum_Y','');
        $CurRange=Yii::app()->request->getParam('CurRange','');
        $KI=Yii::app()->request->getParam('KI','');
        $ZeroCurADC=Yii::app()->request->getParam('ZeroCurADC','');
        $DisChaLim_R=Yii::app()->request->getParam('DisChaLim_R','');
        $DisChaLim_O=Yii::app()->request->getParam('DisChaLim_O','');
        $DisChaLim_Y=Yii::app()->request->getParam('DisChaLim_Y','');
        $ChaLim_R=Yii::app()->request->getParam('ChaLim_R','');
        $ChaLim_O=Yii::app()->request->getParam('ChaLim_O','');
        $ChaLim_Y=Yii::app()->request->getParam('ChaLim_Y','');
        $HiVolLim_R=Yii::app()->request->getParam('HiVolLim_R','');
        $HiVolLim_O=Yii::app()->request->getParam('HiVolLim_O','');
        $HiVolLim_Y=Yii::app()->request->getParam('HiVolLim_Y','');
        $LoVolLim_R=Yii::app()->request->getParam('LoVolLim_R','');
        $LoVolLim_O=Yii::app()->request->getParam('LoVolLim_O','');
        $LoVolLim_Y=Yii::app()->request->getParam('LoVolLim_Y','');
        $ChaCriterion=Yii::app()->request->getParam('ChaCriterion','');


        if ($sn_key) {
            $row = array();
            $sn_key !='' && $row['sn_key']=$sn_key;
            $sid !='' && $row['sid']=$sid;
            $Groups !='' && $row['Groups']=$Groups;
            $GroBats !='' && $row['GroBats']=$GroBats;
            $Time_MR !='' && $row['Time_MR']=$Time_MR;
            $Time_MV !='' && $row['Time_MV']=$Time_MV;
            $MaxTem_R !='' && $row['MaxTem_R']=$MaxTem_R;
            $MaxTem_O !='' && $row['MaxTem_O']=$MaxTem_O;
            $MaxTem_Y !='' && $row['MaxTem_Y']=$MaxTem_Y;
            $MinTem_R !='' && $row['MinTem_R']=$MinTem_R;
            $MinTem_O !='' && $row['MinTem_O']=$MinTem_O;
            $MinTem_Y !='' && $row['MinTem_Y']=$MinTem_Y;
            $MaxHum_R !='' && $row['MaxHum_R']=$MaxHum_R;
            $MaxHum_O !='' && $row['MaxHum_O']=$MaxHum_O;
            $MaxHum_Y !='' && $row['MaxHum_Y']=$MaxHum_Y;
            $MinHum_R !='' && $row['MinHum_R']=$MinHum_R;
            $MinHum_O !='' && $row['MinHum_O']=$MinHum_O;
            $MinHum_Y !='' && $row['MinHum_Y']=$MinHum_Y;
            $CurRange !='' && $row['CurRange']=$CurRange;
            $KI !='' && $row['KI']=$KI;
            $ZeroCurADC !='' && $row['ZeroCurADC']=$ZeroCurADC;
            $DisChaLim_R !='' && $row['DisChaLim_R']=$DisChaLim_R;
            $DisChaLim_O !='' && $row['DisChaLim_O']=$DisChaLim_O;
            $DisChaLim_Y !='' && $row['DisChaLim_Y']=$DisChaLim_Y;
            $ChaLim_R !='' && $row['ChaLim_R']=$ChaLim_R;
            $ChaLim_O !='' && $row['ChaLim_O']=$ChaLim_O;
            $ChaLim_Y !='' && $row['ChaLim_Y']=$ChaLim_Y;
            $HiVolLim_R !='' && $row['HiVolLim_R']=$HiVolLim_R;
            $HiVolLim_O !='' && $row['HiVolLim_O']=$HiVolLim_O;
            $HiVolLim_Y !='' && $row['HiVolLim_Y']=$HiVolLim_Y;
            $LoVolLim_R !='' && $row['LoVolLim_R']=$LoVolLim_R;
            $LoVolLim_O !='' && $row['LoVolLim_O']=$LoVolLim_O;
            $LoVolLim_Y !='' && $row['LoVolLim_Y']=$LoVolLim_Y;
            $ChaCriterion !='' && $row['ChaCriterion']=$ChaCriterion;


            $upsql = Utils::buildUpdateSQL($row);
            $sql = "update {{station_param}} set ".$upsql." where sn_key=".$sn_key;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            // 修改完了之后修改站相关的参数
            $sitearray = array();
            $sitearray['serial_number']=$sn_key;
            $sitearray['sid']=$sid;
            $sitearray['groups']=$Groups;
            $sitearray['batteries']=$GroBats;
            $upsitesql = Utils::buildUpdateSQL($sitearray);
            Yii::app()->bms->createCommand("update my_site set ".$upsitesql." where serial_number=".$sn_key)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'sn_key'=>$sn_key,
                    'sid' =>$sid,
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有站参数数据！'
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