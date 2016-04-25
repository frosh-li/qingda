<?php

class GroupparaController extends Controller
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
            ->from('{{group_parameter}}')
            ->order('gid desc')
            ->queryAll();
        var_dump($batteryparm);
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
        $id = Yii::app()->request->getParam('gid',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select * from {{group_parameter}}
                    where gid=" . $id;
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
    public function actionUpdate()
    {
        $id = Yii::app()->request->getParam('gid' ,0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $group_sn_key=Yii::app()->request->getParam('group_sn_key','');
        $sid=Yii::app()->request->getParam('sid','');
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

        if ($id) {
            $row = array();
            $group_sn_key != '' && $row['group_sn_key']=$group_sn_key;
            $sid != '' && $row['sid']=$sid;
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

            $upsql = Utils::buildUpdateSQL($row);
            $sql = "update  {{group_parameter}} set ".$upsql." where gid=".$id;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array(
                    'sid' =>$sid,
                    'gid'=>$id,
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