<?php

class RunlogController extends Controller
{
	public function actionIndex()
	{
        $this->setPageCount();
		$type = Yii::app()->request->getParam('type' ,0);
        $start =substr(Yii::app()->request->getParam('start'),0,10);
        $end = substr(Yii::app()->request->getParam('end'),0,10);
        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));
        

        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', $start);
            //$where .= ' and modify_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', $end);
            //$where .= ' and modify_time <= "'.$end.'"';
        }

        $this->setPageCount();
        $logs = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{running_log}}')
            ->where($where)
            ->limit($this->count)
            ->offset(($this->page-1)*$this->count)
            ->order('modify_time desc')
            ->queryAll();
        $totals = Yii::app()->db->createCommand()
            ->select('count(*) as totals')
            ->from('{{running_log}}')
            ->where($where)
            ->order('id desc')
            ->queryScalar();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        if ($logs) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;
            $ret['data']['totals'] = intval($totals);
            foreach($logs as $key=>$value){
                $ret['data']['list'][] = $value;
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点！'
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
