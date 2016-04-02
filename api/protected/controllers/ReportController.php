<?php

class ReportController  extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';
    // 电池充放电
    public function actionBettary()
    {
        $alarm_sn = Yii::app()->request->getParam('alarm_sn',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $sql = "select * from {{general_alarm}} where alarm_sn= ".$alarm_sn;
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            $ret['data'] = $row;
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该报警信息！'
            );
        }
        echo json_encode($ret);
    }
    // 电池使用年限
    public function actionByearlog()
    {
        $id = Yii::app()->request->getParam('id',0);
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $this->setPageCount();

        $sql = "select * from {{battery_info}} ";
        $bettery = Yii::app()->db->createCommand($sql)->queryAll();
        if ($bettery) {
            foreach ($bettery as $index => $item) {
                $b[$item['sid']] = $item;
            }
        }

        if ($id) {
            $sql = "select sid from {{site}} where serial_number in (".$id.")";
            $sids =  Yii::app()->db->createCommand($sql)->queryColumn();

            $bets = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{battery_module_history}}')
                ->where('sid in ('.implode(',',$sids).')')
                ->limit($this->count)
                ->offset(($this->page - 1) * $this->count)
                ->order('record_time desc')
                ->queryAll();

        }else{
            $bets = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{battery_module_history}}')
                ->limit($this->count)
                ->offset(($this->page - 1) * $this->count)
                ->order('record_time desc')
                ->queryAll();
        }
        if ($bets) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach ($bets as $index => $bet) {
                $ret['data']['list'][] = array(
                    'brand'=>isset($b[$bet['sid']]) ? $b[$bet['sid']]['battery_factory']:'',
                    'battery_date'=>isset($b[$bet['sid']]) ? $b[$bet['sid']]['battery_date']:'',
                    'battery_oum'=>isset($b[$bet['sid']]) ? $b[$bet['sid']]['battery_oum']:'',
                    'battery_voltage'=>isset($b[$bet['sid']]) ?$b[$bet['sid']]['battery_voltage']:'',
                    'battery_scrap_date'=>isset($b[$bet['sid']]) ?$b[$bet['sid']]['battery_scrap_date']:'',
                    'battery_life'=>isset($b[$bet['sid']]) ?$b[$bet['sid']]['battery_life']:'',
                    'battery_install_date'=>isset($b[$bet['sid']]) ? date('Y-m-d H:i:s',$b[$bet['sid']]['create_time']) :'',
                    'R'=>$bet['R'],
                    'U'=>$bet['U'],
                    'sn_key'=>$bet['sn_key'],
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无电池信息！'
            );
        }
        echo json_encode($ret);
    }
    //偏离趋势图
    public function actionIndex()
    {
        $type = Yii::app()->request->getParam('type',0);
        $begin = Yii::app()->request->getParam('begin',0);
        $end = Yii::app()->request->getParam('end',0);
        $id = Yii::app()->request->getParam('id',0);
        $where = '1=1 ';
        if ($type != 0) {
            $where .= ' and alarm_emergency_level='.$type;
        }
        if ($id != 0) {
            $where .= '  and alarm_para1_name in(' . $id . ') ';
        }
        $where .= ' and alarm_recovered_time >='.$begin . " and alarm_recovered_time <= ".$end;
        $this->setPageCount();
        $alarms = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{general_alarm}}')
            ->where($where)
            ->limit($this->count)
            ->offset(($this->page - 1) * $this->count)
            ->order('alarm_occur_time desc')
            ->queryAll();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        if ($alarms) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($alarms as $key=>$value){
                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无报警信息！'
            );
        }
        echo json_encode($ret);
    }

    public function actionUilog()
    {

    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GerneralParameters the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=GerneralParameters::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}
