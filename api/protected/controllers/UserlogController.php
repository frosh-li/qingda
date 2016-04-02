<?php

class UserlogController extends Controller
{
    public $types = array(1=>'登录相关',2=>'设置相关',3=>'其他');
	public function actionIndex()
	{
		$type = Yii::app()->request->getParam('type' ,0);
        $where = ' 1 =1 ';
        if ($type != 0 ) {
            $where .= ' and type='.$type;
        }
        $this->setPageCount();
        $logs = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{action_log}}')
            ->where($where)
            ->limit($this->count)
            ->offset(($this->page-1)*$this->count)
            ->order('id desc')
            ->queryAll();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        if ($logs) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($logs as $key=>$value){
                $value['type'] = $this->types[$value['type']];
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