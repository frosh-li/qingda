<?php

class MapController extends Controller
{
	public function actionSitesinfo()
	{
        $id = Yii::app()->request->getParam('id',0);
        $sql = "select
                s.sid,
                s.site_name,s.serial_number ,
                s.emergency_person,s.site_location,
                ui.ups_factory,ui.ups_service_phone
                from {{site}} as s left JOIN  {{ups_info}} as ui on s.sid=ui.sid ";
        if ($id) {
            $sql .= " where s.serial_number in (" . $id . ")";
        }
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($rows) {
            foreach ($rows as $key => $value) {
                $row['sid'] = $value['sid'];
                $row['site_name'] = $value['site_name'];
                $row['sn_key'] = $value['serial_number'];
                $row['manager'] = $value['emergency_person'];
                $row['emergency_person'] = $value['emergency_person'];
                $row['site_location'] = $value['site_location'];
                $row['ups_factory'] = $value['ups_factory'];
                $row['ups_service_phone'] = $value['ups_service_phone'];

                $ret['data']['list'][] = $row;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有站点信息数据！'
            );
        }
        echo json_encode($ret);
	}
    public function actionSites()
    {
        $sn = Yii::app()->request->getParam('sn',0);
        $sites = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{site}}')
            ->where('serial_number = :sn', array(':sn'=>$sn))
            ->order('id desc')
            ->queryAll();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sites as $key=>$value){
                $row = array();
                $row['id'] = $value['sid'];
                $row['serial_number'] = $value['serial_number'];
                $row['site_longitude'] = $value['site_longitude'];
                $row['site_latitude'] = $value['site_latitude'];
                $row['site_name'] = $value['site_name'];
                $row['status'] = 0;
                $ret['data']['list'][] = $row;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有站数据！'
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