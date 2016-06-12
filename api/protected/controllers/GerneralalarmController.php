<?php

class GerneralalarmController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

    public function actionView()
    {
        $alarm_sn = Yii::app()->request->getParam('alarm_sn',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $sql = "select * from {{general_alarm}} where alarm_sn= ".$alarm_sn;
        $row = Yii::app()->bms->createCommand($sql)->queryRow();
        if ($row) {
            $sql = "select * from {{bms_info}} ";
            $bmsinfo = Yii::app()->db->createCommand($sql)->queryRow();
            if ($bmsinfo) {
                $row['bms_phone'] = $bmsinfo['bms_phone'];
                $row['bms_tel'] = $bmsinfo['bms_tel'];
            }else{
                $row['bms_phone'] = '暂无';
                $row['bms_tel'] = '暂无';
            }
            $ret['data'] = $row;
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该报警信息！'
            );
        }
        echo json_encode($ret);
    }

    public function actionUpdate()
    {
        $alarm_sn = Yii::app()->request->getParam('alarm_sn',0);
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        if ($alarm_sn) {
            $row = array();
            $row['alarm_process_and_memo'] = Yii::app()->request->getParam('alarm_memo','');
            $row['alarm_update_time'] = date("Y-m-d H:i:s");
            $upsql = Utils::buildUpdateSQL($row);
            $sql = "update {{general_alarm}} set ".$upsql." where alarm_sn=".$alarm_sn;
            $exec = Yii::app()->bms->createCommand($sql)->execute();
            if ($exec) {
                $ret['data'] = array('alarm_sn'=>$alarm_sn);
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'更新报警失败！'
                );
            }
        }
    }
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

        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');

        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and alarm_recovered_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and alarm_recovered_time <= "'.$end.'"';
        }

        $this->setPageCount();
        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));
        if ($isDownload == 1) {
            $alarms = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{general_alarm_history}}')
            ->where($where)
            ->limit(5000)
            ->offset(0)
            ->order('alarm_occur_time desc')
            ->queryAll();
        }else{
            $alarms = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{general_alarm_history}}')
            ->where($where)
            ->limit($this->count)
            ->offset(($this->page - 1) * $this->count)
            ->order('alarm_occur_time desc')
            ->queryAll();
        }
        
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
        if ($isDownload == 1) {
            Yii::$enableIncludePath = false;
            Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
            $objPHPExcel = new PHPExcel();
            $workSheet = $objPHPExcel->setActiveSheetIndex(0);
            // Add some data
            $workSheet->setCellValue('A1', '站名')
                ->setCellValue('B1', '站号')
                ->setCellValue('C1', '组号')
                ->setCellValue('D1', '电池号')
                ->setCellValue('E1', '时间')
                ->setCellValue('F1', '警情内容')
                ->setCellValue('G1', '数值')
                ->setCellValue('H1', '建议处理方式');
            $index = 1;
            foreach ($ret['data']['list'] as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, isset($v['alram_equipment']) ? $v['alram_equipment']:"")
                    ->setCellValue('B'.$index, isset($v['alarm_para1_name']) ? $v['alarm_para1_name']:"")
                    ->setCellValue('C'.$index, isset($v['alarm_para2_name']) ? $v['alarm_para2_name']:"")
                    ->setCellValue('D'.$index, isset($v['alarm_para3_name']) ? $v['alarm_para3_name']:"")
                    ->setCellValue('E'.$index, isset($v['alarm_occur_time']) ? $v['alarm_occur_time']:"")
                    ->setCellValue('F'.$index, isset($v['alarm_content']) ? $v['alarm_content']:"")
                    ->setCellValue('G'.$index, isset($v['alarm_para1_value']) ? $v['alarm_para1_value']:"")
                    ->setCellValue('H'.$index, isset($v['alarm_suggestion']) ? $v['alarm_suggestion']:"");
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('历史报警表');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="alarms.xls"');
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
