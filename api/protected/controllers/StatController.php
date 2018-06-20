<?php

class StatController extends Controller
{
	public function actionIndex()
	{
        date_default_timezone_set("Asia/Shanghai");
        //需要根据my_sysuser的area区域字段来区分数据报警
        //mysite的aid可以关联tree的id
        $areas = "select area from my_sysuser where id = ".$_SESSION["uid"];
        $auths = Yii::app()->db->createCommand($areas)->queryScalar();
        // echo $auths;
        $sn_key_list = array();
        if ($auths != "*"){
            $sql = "select serial_number from my_site where aid in ($auths)";
            $search = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($search as $value) {
                $sn_key_list[] = $value['serial_number']/10000;
            }
        }
        unset($where,$sql);
        $offlinealertfilter = $alertfilter = '';
        if (count($sn_key_list) > 0){
            $alertfilter = 'where floor(sn_key/10000) in ('.implode(',', $sn_key_list).') ';
            $offlinealertfilter = 'and floor(site.serial_number/10000) in ('.implode(',', $sn_key_list).') ';
        }

		$sql = "select count(*) from {{station_module}} $alertfilter";
        $online = Yii::app()->bms->createCommand($sql)->queryScalar();
        $sql = "select count(*) from my_site as site where is_checked=1 $offlinealertfilter and site.serial_number not in (select sn_key from tb_station_module)";
        $total = Yii::app()->db->createCommand($sql)->queryScalar();

        $statusSql = Yii::app()->db->createCommand('select * from tb_ds_working_parameters')->queryAll();

        //查询邮票工作状态
        $upiao = Yii::app()->db->createCommand('select record_time from tb_station_module order by record_time desc limit 0,1')->queryScalar();
        // var_dump($upiao);
        // var_dump(date('Y-m-d H:i:s'));
        // var_dump(floor(strtotime(date("Y-m-d H:i:s")) - strtotime($upiao))%86400%60);
        $upiaoStatus = empty($upiao) || ((strtotime(date("Y-m-d H:i:s")) - strtotime($upiao)) > 30) ? 0:1;

        $ret['response'] = array(
            'code' => 0,
            'msg' => '站点链接统计'
        );
        $sql = "select refresh from my_sysuser where id = $_SESSION[uid]";
        $refresh = Yii::app()->bms->createCommand($sql)->queryScalar();
        $ret['data'] = array(
            'online'=>$online,
            'offline'=>$total,
            'refresh'=>$refresh,
            'dismap'=>Yii::app()->config->get("dismap"),
            'sms_on_off'=>Yii::app()->config->get("sms_on_off"),
            'email_on_off'=>Yii::app()->config->get("email_on_off"),
            'light_on_off'=>Yii::app()->config->get("light_on_off"),
            'voice_on_off'=>Yii::app()->config->get("voice_on_off"),
            'status'=>$statusSql[0],
            'upiao' => $upiaoStatus,
            'server_date'=>strtotime(date("Y-m-d H:i:s")),
            'server_upiao'=>strtotime($upiao),
            'last_update' => $upiao
        );
        echo json_encode($ret);
	}

    public function actionInfo() {
        $session = $this->getSession();
        $session->registerEnterTime();
        $data = array(
                    'dismap'=>Yii::app()->config->get("dismap"),
                    'refresh'=>Yii::app()->config->get("refresh"),
                    'startTime' => $session->getEnterTime(),
                    'name' => $session->getUserName(),
                    'loginTime' => $session->getLoginTime(),
                    'time' => date('Y-m-d H:i:s'));
        $this->ajaxReturn(0, '', $data);
    }
}