<?php

class UserlogController extends Controller
{
    public $types = array(1=>'登录相关',2=>'设置相关',3=>'其他');
	public function actionIndex()
	{
        $this->setPageCount();
		$type = Yii::app()->request->getParam('type' ,0);
        $start =substr(Yii::app()->request->getParam('start'),0,10);
        $end = substr(Yii::app()->request->getParam('end'),0,10);
        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));
        

        $where = ' 1 =1 ';
        //if ($type != 0 ) {
            $where .= ' and type='.$type;
        //}
        if($start){
            $start = date('Y-m-d H:i:s', $start);
            $where .= ' and modify_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', $end);
            $where .= ' and modify_time <= "'.$end.'"';
        }

        //add by pk
        $userList = Yii::app()->db->createCommand()
            ->select('*')
            ->from('my_sysuser')
            ->queryAll(); //queryRow
        $userIds = array();
        foreach ($userList as $value){
            if ($value['area'] == '*' || $_SESSION['area'] == '*'){
                $userIds[] = $value['id'];
            }else{
                $area_arr = explode(',',$value['area']); //大集合
                $auth_arr = explode(',',$_SESSION['area']); //小集合
                if ($auth_arr == array_intersect($auth_arr,$area_arr)){
                    $userIds[] = $value['id'];
                }
            }
        }
        $userIds = join(',',$userIds);
        // var_dump($userIds);
        $role = '3';
        switch($_SESSION['role']){
            case 1:
                $role = '1,2,3';
                break;
            case 2:
                $role = '2,3';
                break;
        }
        // $sql = "SELECT * FROM `my_action_log` a,my_sysuser b where a.uid = b.id and b.role in ($role) and a.uid in ($userIds)";
        // echo $sql;
        
        $where.= " and my_sysuser.role in ($role) and my_action_log.uid in ($userIds)";

        $this->setPageCount();
        if ($isDownload == 1) {
            $logs = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{action_log}}')
            ->join('{{sysuser}}','{{action_log}}.uid = {{sysuser}}.id')
            ->where($where)
            ->limit(5000)
            ->offset(0)
            ->order('modify_time desc')
            ->queryAll();
        }else{
            $logs = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{action_log}}')
            ->join('{{sysuser}}','{{action_log}}.uid = {{sysuser}}.id')
            ->where($where)
            ->limit($this->count)
            ->offset(($this->page-1)*$this->count)
            ->order('modify_time desc')
            ->queryAll();
        }
        $totals = Yii::app()->db->createCommand()
            ->select('count(*) as totals')
            ->from('{{action_log}}')
            ->join('{{sysuser}}','{{action_log}}.uid = {{sysuser}}.id')
            ->where($where)
            ->order('{{action_log}}.id desc')
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
                $value['type'] = $this->types[$value['type']];
                $ret['data']['list'][] = $value;
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点！'
            );
        }

        if ($isDownload == 1) {
            Yii::$enableIncludePath = false;
            Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
            $objPHPExcel = new PHPExcel();
            $workSheet = $objPHPExcel->setActiveSheetIndex(0);
            // Add some data
            $workSheet->setCellValue('A1', '用户')
                ->setCellValue('B1', '操作内容')
                ->setCellValue('C1', '操作时间');
            $index = 1;
            foreach ($ret['data']['list'] as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, $v['username'])
                    ->setCellValue('B'.$index, $v['content'])
                    ->setCellValue('C'.$index, $v['modify_time']);
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('UI日志设置');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="userlog.xls"');
            header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        } else {
            echo json_encode($ret);
        }
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
