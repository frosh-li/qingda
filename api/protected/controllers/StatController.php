<?php

class StatController extends Controller
{
	public function actionIndex()
	{
		$sql = "select count(*) from {{station_module}}";
        $online = Yii::app()->bms->createCommand($sql)->queryScalar();
        $sql = "select count(*) from {{site}} where is_checked=0";
        $total = Yii::app()->db->createCommand($sql)->queryScalar();
        $ret['response'] = array(
            'code' => 0,
            'msg' => '站点链接统计'
        );
        $ret['data'] = array(
            'online'=>$total,
            'offline'=>$online-$total,
        );
        echo json_encode($ret);
	}

    public function actionInfo() {
        $session = $this->getSession();
        $session->registerEnterTime();
        $data = array(
                    'startTime' => $session->getEnterTime(),
                    'name' => $session->getUserName(),
                    'loginTime' => $session->getLoginTime(),
                    'time' => date('Y-m-d H:i:s'));
        $this->ajaxReturn(0, '', $data);
    }
}