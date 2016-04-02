<?php

class BatteryparaController extends Controller
{
	public function actionIndex()
	{
        $this->setPageCount();
        $site = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{site}}')
            ->queryAll();
        $data = array();
        if ($site) {
            foreach ($site as $key => $value) {
                $data[$value['sid']] = $value;
            }
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $batteryparm = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{battery_parameter}}')
            ->limit($this->count)
            ->offset(($this->page-1)*$this->count)
            ->order('bid desc')
            ->queryAll();
        if ($batteryparm) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($batteryparm as $key=>$value){
                if (isset($data[$value['sid']])) {
                    $value['site_name'] = $data[$value['sid']]['site_name'];
                }else{
                    $value['site_name'] = '未添加站点';
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
        $id = Yii::app()->request->getParam('bid',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select * from {{battery_parameter}}
                    where bid=" . $id;
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
        $battery_sn_key=Yii::app()->request->getParam('battery_sn_key','');
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        $sid=Yii::app()->request->getParam('sid','');
        $gid=Yii::app()->request->getParam('gid','');
        $bid = Yii::app()->request->getParam('bid' ,0);
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
        if ($battery_sn_key) {
            $row = array();
            $battery_sn_key !='' && $row['battery_sn_key']=$battery_sn_key;
            $sid !='' && $row['sid']=$sid;
            $gid !='' && $row['gid']=$gid;
            $bid !='' && $row['bid']=$bid;
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

            $upsql = Utils::buildUpdateSQL($row);
            $sql = "update   {{battery_parameter}} set ".$upsql." where battery_sn_key=".$battery_sn_key;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'battery_sn_key'=>$battery_sn_key,
                    'sid' =>$sid,
                    'gid'=>$gid,
                    'bid'=>$bid
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