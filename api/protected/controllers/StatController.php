<?php

class StatController extends Controller
{
	public function actionIndex()
	{
        date_default_timezone_set("Asia/Shanghai");
		$sql = "select count(*) from {{station_module}}";
        $online = Yii::app()->bms->createCommand($sql)->queryScalar();
        $sql = "select count(*) from my_site as site where is_checked=1 and  site.serial_number not in (select sn_key from tb_station_module)";
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
        $ret['data'] = array(
            'online'=>$online,
            'offline'=>$total,
            'refresh'=>Yii::app()->config->get("refresh"),
            'dismap'=>Yii::app()->config->get("dismap"),
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