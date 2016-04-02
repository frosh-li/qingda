<?php

class StatController extends Controller
{
	public function actionIndex()
	{
		$sql = "select count(*) from {{station_module}}";
        $online = Yii::app()->bms->createCommand($sql)->queryScalar();
        $sql = "select count(*) from {{site}} where is_checked=1";
        $total = Yii::app()->db->createCommand($sql)->queryScalar();
        $ret['response'] = array(
            'code' => 0,
            'msg' => '站点链接统计'
        );
        $ret['data'] = array(
            'online'=>$total,
            'offline'=>$online,
        );
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