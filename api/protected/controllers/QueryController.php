<?php

class QueryController extends Controller
{
    //站点实时数据
	public function actionIndex()
	{
        $this->setPageCount();
        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));
        $id = Yii::app()->request->getParam('id',0);
        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');
        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and record_time <= "'.$end.'"';
        }

        // if ($id) {
        //     $arr = explode(',',$id);
        //     $temp = array();
        //     foreach ($arr as $key => $value) {
        //         $temp[] = $value.'0000';
        //     }
        //     $id =  implode(',',$temp);

        //     $sites = Yii::app()->bms->createCommand()
        //         ->select('*')
        //         ->from('{{station_module_history}}')
        //         ->where('sn_key in('.$id.')')
        //         ->limit($this->count)
        //         ->offset(($this->page - 1) * $this->count)
        //         ->order('record_time desc')
        //         ->queryAll();
        // }else{
            $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{station_module_history}}')
                ->where($where)
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        //}
        //
        

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sites as $key=>$value){
                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点数据！'
            );
        }
        if ($isDownload == 1) {
            Yii::$enableIncludePath = false;
            Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
            $objPHPExcel = new PHPExcel();
            $workSheet = $objPHPExcel->setActiveSheetIndex(0);
            // Add some data
            $workSheet->setCellValue('A1', '序号')
                ->setCellValue('B1', '站号')
                ->setCellValue('C1', '时间')
                ->setCellValue('D1', '总电流')
                ->setCellValue('E1', '平均电压')
                ->setCellValue('F1', '环境温度')
                ->setCellValue('G1', '环境温度上限')
                ->setCellValue('H1', '环境温度下限')
                ->setCellValue('I1', '环境湿度')
                ->setCellValue('J1', '环境湿度上限')
                ->setCellValue('K1', '环境湿度下限')
                ->setCellValue('L1', '电池状态')
                ->setCellValue('M1', '预估后备时间');
            $index = 1;
            foreach ($result as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, $index - 1)
                    ->setCellValue('B'.$index, $v['sid'])
                    ->setCellValue('C'.$index, $v['record_time'])
                    ->setCellValue('D'.$index, $v['a'])
                    ->setCellValue('E'.$index, $v['v'])
                    ->setCellValue('F'.$index, $v['temperature'])
                    ->setCellValue('G'.$index, $v['temperature_max'])
                    ->setCellValue('H'.$index, $v['temperature_min'])
                    ->setCellValue('I'.$index, $v['humidity'])
                    ->setCellValue('J'.$index, $v['humidity_max'])
                    ->setCellValue('K'.$index, $v['humidity_min'])
                    ->setCellValue('L'.$index, $v['battery_state'])
                    ->setCellValue('M'.$index, $v['reserve_time']);                    
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('偏离趋势报表');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="deviation_trend.xls"');
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
    //站点数据折线图
    public function actionStationchart()
    {
        $field = Yii::app()->request->getParam('field','T');
        $id = Yii::app()->request->getParam('id',0);
        $sql = "select * from {{site}} ";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $sitearr = array();
        if ($rows) {
            foreach ($rows as $key => $value) {
                $sitearr[$value['sid']] = $value;
            }

        }
        if ($id) {
            //$arr = explode(',',$id);
            //$temp = array();
            //foreach ($arr as $key => $value) {
            //    $temp[] = $value.'0000';
            //}
            //$id =  implode(',',$temp);
            if (is_numeric($id)) {
                $sites = Yii::app()->bms->createCommand()
                    ->select($field . ',sid')
                    ->from('{{station_module_history}}')
                    ->where('sn_key in(' . $id . ')')
                    ->limit($this->count)
                    ->offset(($this->page - 1) * $this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }else{
                $arr = explode(',',$id);

                $sites = Yii::app()->bms->createCommand()
                    ->select('sid,'.$field)
                    ->from('{{station_module_history}}')
                    ->where('sn_key in('.$id.')')
                    //->limit($this->count)
                    ->limit(count($arr))
                    ->offset(($this->page - 1) * $this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }

        }else{
            $sites = Yii::app()->bms->createCommand()
                ->select($field.',sid')
                ->from('{{station_module_history}}')
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        }
        $ret['response'] = array(
            'code' => 0,
            'msg' => '字段'.$field
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sites as $key=>$value){
                $row = array();
                $row['value'] = $value[$field];

                if (isset($sitearr[$value['sid']])) {
                    $sitename = $sitearr[$value['sid']]['site_name'];
                }else{
                    $sitename = '未知';
                }
                $row['name'] = $sitename;
                // 这个待定
                $row['status'] = 0;
                $row['id'] = $value['sid'];

                $ret['data']['list'][] = $row;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点数据！'
            );
        }

        echo json_encode($ret);
    }
    //组实时数据
    public function actionGroupmodule()
    {
        $this->setPageCount();
        $id = Yii::app()->request->getParam('id',0);
        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');
        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and record_time <= "'.$end.'"';
        }
        // if ($id) {
        //     $arr = explode(',',$id);
        //     $temp = array();
        //     foreach ($arr as $key => $value) {
        //         $temp[] = $value.'00';
        //     }
        //     $id =  implode(',',$temp);
        //     $sites = Yii::app()->bms->createCommand()
        //         ->select('*')
        //         ->from('{{group_module_history}}')
        //         ->where('sn_key in('.$id.')')
        //         ->limit($this->count)
        //         ->offset(($this->page - 1) * $this->count)
        //         ->order('record_time desc')
        //         ->queryAll();
        // }else{
            $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{group_module_history}}')
                ->where($where)
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        //}

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sites as $key=>$value){
                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无组数据！'
            );
        }

        echo json_encode($ret);
    }
    //组实时数据折线图
    public function actionGroupchart()
    {
        $field = Yii::app()->request->getParam('field','I');
        $this->setPageCount();
        $id = Yii::app()->request->getParam('id',0);
        if ($id) {
            //$arr = explode(',',$id);
            //$temp = array();
            //foreach ($arr as $key => $value) {
            //    $temp[] = $value.'00';
            //}
            //$id =  implode(',',$temp);
            if (is_numeric($id)) {
                $sites = Yii::app()->bms->createCommand()
                    ->select($field.',gid,sn_key')
                    ->from('{{group_module_history}}')
                    ->where('sn_key in('.$id.')')
                    ->limit($this->count)
                    ->offset(($this->page - 1) * $this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }else{
                $arr = explode(',',$id);

                $sites = Yii::app()->bms->createCommand()
                    ->select($field.',gid,sn_key')
                    ->from('{{group_module_history}}')
                    ->where('sn_key in('.$id.')')
                    ->limit(count($arr))
                    ->offset(($this->page - 1) * $this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }

        }else{
            $sites = Yii::app()->bms->createCommand()
                ->select($field.',gid,sn_key')
                ->from('{{group_module_history}}')
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => '字段'.$field
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sites as $key=>$value){
                $row = array();
                $row['value'] = $value[$field];
                $row['name'] = $value['gid'];
                //这个待定
                $row['status'] = 0;
                $row['sn_key'] = $value['sn_key'];
                $row['id'] = $value['gid'];
                $ret['data']['list'][] = $row;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无组数据！'
            );
        }

        echo json_encode($ret);
    }
    // 电池实时数据
    public function actionBatterymodule()
    {
        $this->setPageCount();
        $id = Yii::app()->request->getParam('id',0);
        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');
        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and record_time <= "'.$end.'"';
        }
        // if ($id) {
        //     $sites = Yii::app()->bms->createCommand()
        //         ->select('*')
        //         ->from('{{battery_module_history}}')
        //         ->where('sn_key in('.$id.')')
        //         ->limit($this->count)
        //         ->offset(($this->page-1)*$this->count)
        //         ->order('record_time desc')
        //         ->queryAll();
        // }else{
            $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{battery_module_history}}')
                ->where($where)
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        //}

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sites as $key=>$value){
                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无电池数据！'
            );
        }

        echo json_encode($ret);
    }
    // 电池实时数据折线图
    public function actionBatterychart()
    {
        $field = Yii::app()->request->getParam('field','U');
        $this->setPageCount();
        $id = Yii::app()->request->getParam('id',0);
        if ($id) {
            if (is_numeric($id)) {
                $sites = Yii::app()->bms->createCommand()
                    ->select($field.',mid,sn_key')
                    ->from('{{battery_module_history}}')
                    ->where('sn_key in('.$id.')')
                    ->limit($this->count)
                    ->offset(($this->page-1)*$this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }else{
                $arr = explode(',',$id);

                $sites = Yii::app()->bms->createCommand()
                    ->select($field.',mid,sn_key')
                    ->from('{{battery_module_history}}')
                    ->where('sn_key in('.$id.')')
                    ->limit(count($arr))
                    ->offset(($this->page-1)*$this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }

        }else{
            $sites = Yii::app()->bms->createCommand()
                ->select($field.',mid,sn_key')
                ->from('{{battery_module_history}}')
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        }

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
                $row['value'] = $value[$field];
                $row['name'] = $value['mid'];
                //这个待定
                $row['status'] = 0;
                $row['sn_key'] = $value['sn_key'];
                $row['id'] = $value['mid'];
                $ret['data']['list'][] = $row;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无电池数据！'
            );
        }

        echo json_encode($ret);
    }

    // 实时报警数据
    public function actionGalarm()
    {
        // 具体流程见 警情判断流程判断逻辑.docx 文档
        // 数据直接出，通过command来处理数据
        $id = Yii::app()->request->getParam('id',0);
        $this->setPageCount();
        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');
        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and alarm_occur_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and alarm_occur_time <= "'.$end.'"';
        }
        // if ($id != 0) {
        //     $sites = Yii::app()->bms->createCommand()
        //         ->select('*')
        //         ->from('{{general_alarm_history}}')
        //         ->where('alarm_para1_name in ('.$id.')')
        //         ->limit($this->count)
        //         ->offset(($this->page - 1) * $this->count)
        //         ->order('alarm_occur_time desc')
        //         ->queryAll();
        // }else{
            $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{general_alarm_history}}')
                ->where($where)
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('alarm_occur_time desc')
                ->queryAll();
        //}
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sites as $key=>$value){
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

    public function actionGalarmchart()
    {
        // 报警图标
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $alarm_sn = Yii::app()->request->getParam('alarm_sn',0);
        if ($alarm_sn == 0) {
            $alarms = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{general_alarm_history}}')
                ->order('alarm_occur_time desc')
                ->queryAll();

            if ($alarms) {
                $red = $yellow =$blue = 0;
                foreach ($alarms as $index => $item) {
                    switch ($item['alarm_emergency_level']) {
                        case 1:
                            $red++;
                            break;
                        case 2:
                            $yellow++;
                            break;
                        case 3:
                            $blue++;
                            break;

                        default:

                            break;
                    }
                }
                $ret['data'] = array(
                    'red'=>$red,
                    'yellow'=>$yellow,
                    'blue'=>$blue,
                );

            }else{
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => '暂无报警信息！'
                );
            }
            echo json_encode($ret);
        }else{
            $alarm = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{general_alarm_history}}')
                ->where('alarm_sn='.$alarm_sn)
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('alarm_occur_time desc')
                ->queryRow();
            if ($alarm) {
                $ret['data'] = $this->alarmchartdetail($alarm);
            }else{
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => '暂无报警信息！'
                );
            }
            echo json_encode($ret);
        }
    }

    public function alarmchartdetail($alarm)
    {
        $ret['data'] = array();
        $sql = "select * from {{site}} ";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $sitearr = array();
        if ($rows) {
            foreach ($rows as $key => $value) {
                $sitearr[$value['sid']] = $value;
            }

        }
        if (substr($alarm['alarm_sn'], -4) == '0000') {
            $sites = Yii::app()->bms->createCommand()
                ->select($alarm['alarm_para1_name'] . ',sid')
                ->from('{{station_module_history}}')
                ->where('sn_key in(' . $alarm['alarm_sn'] . ')')
                ->limit($this->count)
                ->offset(($this->page - 1) * $this->count)
                ->order('record_time desc')
                ->queryAll();
            if ($sites) {
                $ret['data']['page'] = $this->page;
                $ret['data']['pageSize'] = $this->count;

                foreach($sites as $key=>$value){
                    $row = array();
                    $row['value'] = $value[$alarm['alarm_para1_name']];

                    if (isset($sitearr[$value['sid']])) {
                        $sitename = $sitearr[$value['sid']]['site_name'];
                    }else{
                        $sitename = '未知';
                    }
                    $row['name'] = $sitename;
                    // 这个待定
                    $row['status'] = 0;
                    $row['id'] = $value['sid'];

                    $ret['data']['list'][] = $row;
                }

            }else{
                $ret['data'] = false;
            }
        }elseif (substr($alarm['alarm_sn'], -4) == '00') {
            $sites = Yii::app()->bms->createCommand()
                ->select($alarm['alarm_para2_name'].',gid,sn_key')
                ->from('{{group_module_history}}')
                ->where('sn_key in('.$alarm['alarm_sn'].')')
                ->limit($this->count)
                ->offset(($this->page - 1) * $this->count)
                ->order('record_time desc')
                ->queryAll();
            if ($sites) {
                $ret['data']['page'] = $this->page;
                $ret['data']['pageSize'] = $this->count;

                foreach($sites as $key=>$value){
                    $row = array();
                    $row['value'] = $value[$alarm['alarm_para2_name']];
                    $row['name'] = $value['gid'];
                    //这个待定
                    $row['status'] = 0;
                    $row['sn_key'] = $value['sn_key'];
                    $row['id'] = $value['gid'];
                    $ret['data']['list'][] = $row;
                }

            }else{
                $ret['data'] = false;
            }
        }else{
            $sites = Yii::app()->bms->createCommand()
                ->select($alarm['alarm_para3_name'].',mid,sn_key')
                ->from('{{battery_module_history}}')
                ->where('sn_key in('.$alarm['alarm_sn'].')')
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
            if ($sites) {
                $ret['data']['page'] = $this->page;
                $ret['data']['pageSize'] = $this->count;

                foreach($sites as $key=>$value){
                    $row = array();
                    $row['value'] = $value[$alarm['alarm_para3_name']];
                    $row['name'] = $value['mid'];
                    //这个待定
                    $row['status'] = 0;
                    $row['sn_key'] = $value['sn_key'];
                    $row['id'] = $value['mid'];
                    $ret['data']['list'][] = $row;
                }
            }
        }

        return $ret['data'];
    }

}