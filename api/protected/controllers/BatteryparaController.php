<?php

class BatteryparaController extends Controller
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

    public function checkPassword($password)
    {
        $sql = "select * from {{program_gerneral_parameters}} where id=1";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row['password'] == $password) {
            return true;
        }
        return false;
    }

    
	public function actionIndex()
	{
        $this->setPageCount();
        $id = Yii::app()->request->getParam('id',0);
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
                $data[substr($value['serial_number'],0,10)] = $value;
            }
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $batteryparm = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{battery_param}}')
            ->order('sn_key asc')
            ->queryAll();
        if($temp){
            $batteryparm = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{battery_param}}')
                ->where('sn_key in ('.implode(",",$temp).')')
                ->order('sn_key asc')
                ->queryAll();
        }
        if ($batteryparm) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($batteryparm as $key=>$value){
                //var_dump(strval(floor($value['battery_sn_key']/10000)));
                if (isset($data[strval(floor($value['sn_key']/10000))])) {
                    
                    $value['site_name'] = $data[strval(floor($value['sn_key']/10000))]['site_name'];
                    $value['sid'] = $data[floor($value['sn_key']/10000)]['sid'];
                }else{
                    $value['site_name'] = '未添加站点';
                    $value['sid'] = '';
                }
                $ret['data']['list'][] = $value;
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无电池参数数据！'
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
            $sql = "select * from {{battery_param}}
                    where sn_key=" . $id;
            $row = Yii::app()->bms->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该电池参数！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该电池参数！'
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
        $id = Yii::app()->request->getParam('bid' ,0);
        $battery_sn_key=Yii::app()->request->getParam('battery_sn_key','');
        $sid=Yii::app()->request->getParam('sid','');
        $gid=Yii::app()->request->getParam('gid','');
        $CurrentAdjust_KV=Yii::app()->request->getParam('CurrentAdjust_KV','');
        $TemperatureAdjust_KT=Yii::app()->request->getParam('TemperatureAdjust_KT','');
        $T0_ADC=Yii::app()->request->getParam('T0_ADC','');
        $T0_Temperature=Yii::app()->request->getParam('T0_Temperature','');
        $T1_ADC=Yii::app()->request->getParam('T1_ADC','');
        $T1_Temperature=Yii::app()->request->getParam('T1_Temperature','');
        $Rin_Span=Yii::app()->request->getParam('Rin_Span','');
        $OSC=Yii::app()->request->getParam('OSC','');
        $BatteryU_H=Yii::app()->request->getParam('BatteryU_H','');
        $BaterryU_L=Yii::app()->request->getParam('BaterryU_L','');
        $Electrode_T_H_Limit=Yii::app()->request->getParam('Electrode_T_H_Limit','');
        $Electrode_T_L_Limit=Yii::app()->request->getParam('Electrode_T_L_Limit','');
        $Rin_High_Limit=Yii::app()->request->getParam('Rin_High_Limit','');
        $Rin_Adjust_KR=Yii::app()->request->getParam('Rin_Adjust_KR','');
        $PreAmp_KA=Yii::app()->request->getParam('PreAmp_KA','');
        $Rin_ExciteI_KI=Yii::app()->request->getParam('Rin_ExciteI_KI','');
        if ($id) {
            $row = array();
            $battery_sn_key !='' && $row['battery_sn_key']=$battery_sn_key;
            $sid !='' && $row['sid']=$sid;
            $gid !='' && $row['gid']=$gid;
            $id !='' && $row['bid']=$id;
            $CurrentAdjust_KV !='' && $row['CurrentAdjust_KV']=$CurrentAdjust_KV;
            $TemperatureAdjust_KT !='' && $row['TemperatureAdjust_KT']=$TemperatureAdjust_KT;
            $T0_ADC !='' && $row['T0_ADC']=$T0_ADC;
            $T0_Temperature !='' && $row['T0_Temperature']=$T0_Temperature;
            $T1_ADC !='' && $row['T1_ADC']=$T1_ADC;
            $T1_Temperature !='' && $row['T1_Temperature']=$T1_Temperature;
            $Rin_Span !='' && $row['Rin_Span']=$Rin_Span;
            $OSC !='' && $row['OSC']=$OSC;
            $BatteryU_H !='' && $row['BatteryU_H']=$BatteryU_H;
            $BaterryU_L !='' && $row['BaterryU_L']=$BaterryU_L;
            $Electrode_T_H_Limit !='' && $row['Electrode_T_H_Limit']=$Electrode_T_H_Limit;
            $Electrode_T_L_Limit !='' && $row['Electrode_T_L_Limit']=$Electrode_T_L_Limit;
            $Rin_High_Limit !='' && $row['Rin_High_Limit']=$Rin_High_Limit;
            $Rin_Adjust_KR !='' && $row['Rin_Adjust_KR']=$Rin_Adjust_KR;
            $PreAmp_KA !='' && $row['PreAmp_KA']=$PreAmp_KA;
            $Rin_ExciteI_KI !='' && $row['Rin_ExciteI_KI']=$Rin_ExciteI_KI;

            $insql = Utils::buildInsertSQL($row);
            $sql = "INSERT INTO  {{battery_parameter}} ".$insql;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'sid' =>$sid,
                    'gid'=>$gid,
                    'bid'=>$id
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有电池参数数据！'
            );
        }
        echo json_encode($ret);
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


        $sn_key=Yii::app()->request->getParam('sn_key','');
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        $KV=Yii::app()->request->getParam('KV','');
        $KT=Yii::app()->request->getParam('KT','');
        $KI=Yii::app()->request->getParam('KI','');
        $T0=Yii::app()->request->getParam('T0','');
        $ADC_T0=Yii::app()->request->getParam('ADC_T0','');
        $T1=Yii::app()->request->getParam('T1','');
        $ADC_T1=Yii::app()->request->getParam('ADC_T1','');
        $MaxU_R=Yii::app()->request->getParam('MaxU_R','');
        $MaxU_O=Yii::app()->request->getParam('MaxU_O','');
        $MaxU_Y=Yii::app()->request->getParam('MaxU_Y','');
        $MinU_R=Yii::app()->request->getParam('MinU_R','');
        $MinU_O=Yii::app()->request->getParam('MinU_O','');
        $MinU_Y=Yii::app()->request->getParam('MinU_Y','');
        $MaxT_R=Yii::app()->request->getParam('MaxT_R','');
        $MaxT_O=Yii::app()->request->getParam('MaxT_O','');
        $MaxT_Y=Yii::app()->request->getParam('MaxT_Y','');
        $MinT_R=Yii::app()->request->getParam('MinT_R','');
        $MinT_O=Yii::app()->request->getParam('MinT_O','');
        $MinT_Y=Yii::app()->request->getParam('MinT_Y','');
        $MaxR_R=Yii::app()->request->getParam('MaxR_R','');
        $MaxR_O=Yii::app()->request->getParam('MaxR_O','');
        $MaxR_Y=Yii::app()->request->getParam('MaxR_Y','');
        $MaxDevU_R=Yii::app()->request->getParam('MaxDevU_R','');
        $MaxDevU_O=Yii::app()->request->getParam('MaxDevU_O','');
        $MaxDevU_Y=Yii::app()->request->getParam('MaxDevU_Y','');
        $MaxDevT_R=Yii::app()->request->getParam('MaxDevT_R','');
        $MaxDevT_O=Yii::app()->request->getParam('MaxDevT_O','');
        $MaxDevT_Y=Yii::app()->request->getParam('MaxDevT_Y','');
        $MaxDevR_R=Yii::app()->request->getParam('MaxDevR_R','');
        $MaxDevR_O=Yii::app()->request->getParam('MaxDevR_O','');
        $MaxDevR_Y=Yii::app()->request->getParam('MaxDevR_Y','');
        if ($sn_key) {
            $row = array();
            $KV != '' && $row['KV']=$KV;
            $KT != '' && $row['KT']=$KT;
            $KI != '' && $row['KI']=$KI;
            $T0 != '' && $row['T0']=$T0;
            $ADC_T0 != '' && $row['ADC_T0']=$ADC_T0;
            $T1 != '' && $row['T1']=$T1;
            $ADC_T1 != '' && $row['ADC_T1']=$ADC_T1;
            $MaxU_R != '' && $row['MaxU_R']=$MaxU_R;
            $MaxU_O != '' && $row['MaxU_O']=$MaxU_O;
            $MaxU_Y != '' && $row['MaxU_Y']=$MaxU_Y;
            $MinU_R != '' && $row['MinU_R']=$MinU_R;
            $MinU_O != '' && $row['MinU_O']=$MinU_O;
            $MinU_Y != '' && $row['MinU_Y']=$MinU_Y;
            $MaxT_R != '' && $row['MaxT_R']=$MaxT_R;
            $MaxT_O != '' && $row['MaxT_O']=$MaxT_O;
            $MaxT_Y != '' && $row['MaxT_Y']=$MaxT_Y;
            $MinT_R != '' && $row['MinT_R']=$MinT_R;
            $MinT_O != '' && $row['MinT_O']=$MinT_O;
            $MinT_Y != '' && $row['MinT_Y']=$MinT_Y;
            $MaxR_R != '' && $row['MaxR_R']=$MaxR_R;
            $MaxR_O != '' && $row['MaxR_O']=$MaxR_O;
            $MaxR_Y != '' && $row['MaxR_Y']=$MaxR_Y;
            $MaxDevU_R != '' && $row['MaxDevU_R']=$MaxDevU_R;
            $MaxDevU_O != '' && $row['MaxDevU_O']=$MaxDevU_O;
            $MaxDevU_Y != '' && $row['MaxDevU_Y']=$MaxDevU_Y;
            $MaxDevT_R != '' && $row['MaxDevT_R']=$MaxDevT_R;
            $MaxDevT_O != '' && $row['MaxDevT_O']=$MaxDevT_O;
            $MaxDevT_Y != '' && $row['MaxDevT_Y']=$MaxDevT_Y;
            $MaxDevR_R != '' && $row['MaxDevR_R']=$MaxDevR_R;
            $MaxDevR_O != '' && $row['MaxDevR_O']=$MaxDevR_O;
            $MaxDevR_Y != '' && $row['MaxDevR_Y']=$MaxDevR_Y;

            $upsql = Utils::buildUpdateSQL($row);
            $sql = "update   {{battery_param}} set ".$upsql." where sn_key=".$sn_key;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'sn_key'=>$sn_key
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有电池参数数据！'
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