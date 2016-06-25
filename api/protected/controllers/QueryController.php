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

        //xl
        $sites = array();
        $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        if(!empty($sns)){
            $sql = "select b.* from tb_station_module_history as b, my_site a ";
            $sql .= " where {$where}";
            $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
            $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc ";
            
            if ($isDownload != 1){
                $sql .= "limit " .($this->page - 1) * $this->count. "," . $this->count;
            }else{
                $sql .= " limit 0, 5000";
            }
            $sites = Yii::app()->bms->createCommand($sql)->queryAll();
        }elseif($sns === false){
            $sql = "select * from tb_station_module_history";
            $offset = ($this->page-1)*$this->count;
            $sql .= " where ".$where;
            $sql .= " order by record_time desc ";
            if ($isDownload != 1){
                $sql .= " limit $offset, $this->count ";
            }else{
                $sql .= " limit 0,5000";
            }
            
            $sites = Yii::app()->bms->createCommand($sql)
                ->queryAll();
        }

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
                // 获取site_name
                $sql = "select site_name from my_site where serial_number={$value['sn_key']}";
                $joinData = Yii::app()->bms->createCommand($sql)->queryScalar();
                $value['site_name'] = $joinData;

                // 获取组数
                $sql = "select count(*) from tb_group_module_history where floor(sn_key/10000)*10000={$value['sn_key']} and record_time='{$value['record_time']}'";
                $joinData = Yii::app()->bms->createCommand($sql)->queryScalar();
                $value['total'] = $joinData;
                // 获取电池数
                $sql = "select count(*) from tb_battery_module_history where floor(sn_key/10000)*10000={$value['sn_key']} and record_time='{$value['record_time']}'";
                $joinData = Yii::app()->bms->createCommand($sql)->queryScalar();
                $value['batteryCount'] = $joinData;
                //     my_ups_info.ups_max_charge,
                //     my_ups_info.ups_max_discharge,
                //     my_ups_info.ups_maintain_date,
                $sql = "select ups_max_charge,ups_max_discharge,ups_maintain_date from my_ups_info where sid={$value['sn_key']} limit 1";
                $joinData = Yii::app()->bms->createCommand($sql)->queryAll();
                if($joinData){
                    foreach($joinData[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }

                // 获取电池充电状态
                $sql = "SELECT BBbCharge+BCbDisCharge as charges 
                FROM tb_battery_module_history
                where floor(sn_key/10000)*10000={$value['sn_key']} and record_time='{$value['record_time']}'";

                $joinData = Yii::app()->bms->createCommand($sql)->queryScalar();
                $value['charges'] = $joinData;

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
            $workSheet->setCellValue('A1', '名称')
                ->setCellValue('B1', '站号')
                ->setCellValue('C1', '时间')
                ->setCellValue('D1', '总电流(A)')
                ->setCellValue('E1', '平均电压(V)')
                ->setCellValue('F1', '环境温度（℃）')
                ->setCellValue('G1', '环境温度上限（℃）')
                ->setCellValue('H1', '环境温度下限（℃）')
                ->setCellValue('I1', '环境湿度（%）')
                ->setCellValue('J1', '环境湿度上限（%）')
                ->setCellValue('K1', '环境湿度下限（%）')
                ->setCellValue('L1', '组数')
                ->setCellValue('M1', '电池数')
                ->setCellValue('N1', '电池状态')
                ->setCellValue('O1', 'UPS状态')
                ->setCellValue('P1', '预估候备时间（H）')
                ->setCellValue('Q1', '候备功率W/h')
                ->setCellValue('R1', '预约维护日期')
                ->setCellValue('S1', '放电日期')
                ->setCellValue('T1', '放电时长')
                ->setCellValue('U1', '最大放电电流（A）')
                ->setCellValue('V1', '最大充电电流（A）');
            $index = 1;
            foreach ($ret['data']['list'] as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, isset($v['site_name']) ? $v['site_name']:"")
                    ->setCellValue('B'.$index, isset($v['sid']) ? $v['sid']:"")
                    ->setCellValue('C'.$index, isset($v['record_time']) ? $v['record_time']:"")
                    ->setCellValue('D'.$index, isset($v['I']) ? $v['I']:"")
                    ->setCellValue('E'.$index, isset($v['U']) ? $v['U']:"")
                    ->setCellValue('F'.$index, isset($v['T']) ? $v['T']:"")
                    ->setCellValue('G'.$index, isset($v['TH']) ? $v['TH']:"")
                    ->setCellValue('H'.$index, isset($v['TL']) ? $v['TL']:"")
                    ->setCellValue('I'.$index, isset($v['Humi']) ? $v['Humi']:"")
                    ->setCellValue('J'.$index, isset($v['HumiH']) ? $v['HumiH']:"")
                    ->setCellValue('K'.$index, isset($v['HumiL']) ? $v['HumiL']:"")
                    ->setCellValue('L'.$index, isset($v['total']) ? $v['total']:"")
                    ->setCellValue('M'.$index, isset($v['batteryCount']) ? $v['batteryCount']:"")
                    ->setCellValue('N'.$index, isset($v['BatteryHealth']) ? $v['BatteryHealth']:"")
                    ->setCellValue('O'.$index, isset($v['charges']) ? ($v['charges'] == 2 ? "充电":$v['charges'] == 1? "放电":"浮充"):"")
                    ->setCellValue('P'.$index, isset($v['BackupTime']) ? $v['BackupTime']:"")
                    ->setCellValue('Q'.$index, isset($v['BackupW']) ? $v['BackupW']:"")
                    ->setCellValue('R'.$index, isset($v['ups_maintain_date']) ? $v['ups_maintain_date']:"")
                    ->setCellValue('S'.$index, isset($v['disChargeDate']) ? $v['disChargeDate']:"")
                    ->setCellValue('T'.$index, isset($v['disChargeLast']) ? $v['disChargeLast']:"")
                    ->setCellValue('U'.$index, isset($v['ups_max_discharge']) ? $v['ups_max_discharge']:"")
                    ->setCellValue('V'.$index, isset($v['ups_max_charge']) ? $v['ups_max_charge']:"");                    
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('站历史数据');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="station_history.xls"');
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
        
        //xl
        $sites = array();
        $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        
        if ($id) {
            //$arr = explode(',',$id);
            //$temp = array();
            //foreach ($arr as $key => $value) {
            //    $temp[] = $value.'0000';
            //}
            //$id =  implode(',',$temp);
            if (is_numeric($id)) {
                if(!empty($sns)){
                    $sql = "select b.{$field}, b.sid from tb_station_module_history as b, my_site a ";
                    $sql .= " where b.sn_key in(" . $id . ')';
                    $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                    $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;
                    $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                }elseif($sns === false){
                    $sites = Yii::app()->bms->createCommand()
                        ->select($field . ',sid')
                        ->from('{{station_module_history}}')
                        ->where('sn_key in(' . $id . ')')
                        ->limit($this->count)
                        ->offset(($this->page - 1) * $this->count)
                        ->order('record_time desc')
                        ->queryAll();
                }
            }else{
                $arr = explode(',',$id);

                if(!empty($sns)){
                    $sql = "select b.{$field}, b.sid from tb_station_module_history as b, my_site a ";
                    $sql .= " where b.sn_key in(" . $id . ')';
                    $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                    $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . count($arr);
                    $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                }elseif($sns === false){
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
            }
        }else{
            if(!empty($sns)){
                $sql = "select b.{$field}, b.sid from tb_station_module_history as b, my_site a ";
                $sql .= " where 1=1 ";
                $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;
                $sites = Yii::app()->bms->createCommand($sql)->queryAll();
            }elseif($sns === false){
                $sites = Yii::app()->bms->createCommand()
                    ->select($field.',sid')
                    ->from('{{station_module_history}}')
                    ->limit($this->count)
                    ->offset(($this->page-1)*$this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }
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
        $where = ' 1 = 1 ';
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
        //xl
        //通过sql直接选择地域进行过滤
           $sites = array();
           $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
           if(!empty($sns)){
                $sql = "select b.* from tb_group_module_history as b, my_site a ";
                $sql .= " where {$where}";
                $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;
                $sites = Yii::app()->bms->createCommand($sql)->queryAll();
            }elseif($sns === false){
                $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{group_module_history}}')
                ->where($where)
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        }
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
        $field = Yii::app()->request->getParam1('field','I');
        $this->setPageCount();
        $id = Yii::app()->request->getParam('id',0);
        
        //xl
        $sites = array();
        $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        if ($id) {
            //$arr = explode(',',$id);
            //$temp = array();
            //foreach ($arr as $key => $value) {
            //    $temp[] = $value.'00';
            //}
            //$id =  implode(',',$temp);
            if (is_numeric($id)) {
                if(!empty($sns)){
                    $sql = "select b.{$field}, b.sid from tb_group_module_history as b, my_site a ";
                    $sql .= " where b.sn_key in(" . $id . ')';
                    $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                    $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;
                    $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                }elseif($sns === false){
                    $sites = Yii::app()->bms->createCommand()
                        ->select($field.',gid,sn_key')
                        ->from('{{group_module_history}}')
                        ->where('sn_key in('.$id.')')
                        ->limit($this->count)
                        ->offset(($this->page - 1) * $this->count)
                        ->order('record_time desc')
                        ->queryAll();
                }
            }else{
                $arr = explode(',',$id);
                if(!empty($sns)){
                    $sql = "select b.{$field}, b.sid from tb_group_module_history as b, my_site a ";
                    $sql .= " where b.sn_key in(" . $id . ')';
                    $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                    $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . count($arr);
                    $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                }elseif($sns === false){
                    $sites = Yii::app()->bms->createCommand()
                        ->select($field.',gid,sn_key')
                        ->from('{{group_module_history}}')
                        ->where('sn_key in('.$id.')')
                        ->limit(count($arr))
                        ->offset(($this->page - 1) * $this->count)
                        ->order('record_time desc')
                        ->queryAll();
                }
            }

        }else{
            if(!empty($sns)){
                $sql = "select b.{$field}, b.sid from tb_group_module_history as b, my_site a ";
                $sql .= " where 1=1 ";
                $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;
                $sites = Yii::app()->bms->createCommand($sql)->queryAll();
            }elseif($sns === false){
                $sites = Yii::app()->bms->createCommand()
                    ->select($field.',gid,sn_key')
                    ->from('{{group_module_history}}')
                    ->limit($this->count)
                    ->offset(($this->page-1)*$this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }
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
        $isDownload = Yii::app()->request->getParam('isdownload',0);
        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and b.record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and b.record_time <= "'.$end.'"';
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
         $sql = "
            select b.* from tb_battery_module_history as b
        ";
          
        $offset = ($this->page-1)*$this->count;
        $sql .= " where ".$where;
        
        //xl
        //通过sql直接选择地域进行过滤
        $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        if(!empty($sns)){
            $sql = "select b.*,a.aid from tb_battery_module_history as b, my_site a ";
            $sql .= " where ".$where;
            $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
            $sql .= "and a.serial_number in (" . implode(",", $sns) .")";
        }

        $sql .= " order by b.record_time desc ";
        if ($isDownload != 1){
            $sql .= " limit $offset, $this->count ";
        }else{
            $sql .= " limit 0,5000";
        }

            $sites = Yii::app()->bms->createCommand($sql)
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
                $sn_key = $value['sn_key'];
                $query = Yii::app()->bms->createCommand()
                            ->select('site_name')
                            ->from('my_site')
                            ->where("floor({$sn_key}/10000) = floor(serial_number/10000)")
                            ->limit(1)
                            ->queryScalar();
                //var_dump($value);
                if($query){
                   $value['site_name'] = $query;
                }
                $query = Yii::app()->bms->createCommand()
                            ->select('*')
                            ->from('tb_battery_parameter')
                            ->where("floor({$sn_key}/10000) = floor(battery_sn_key/10000)")
                            ->limit(1)
                            ->queryAll();
                if($query){
                    foreach($query[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }

                $query = Yii::app()->bms->createCommand()
                            ->select('*')
                            ->from('my_ups_info')
                            ->where("floor({$sn_key}/10000) = floor(sid/10000)")
                            ->limit(1)
                            ->queryAll();
                if($query){
                    foreach($query[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }
            // tb_station_parameter.bytegeStatus_U_upper,
            // tb_station_parameter.bytegeStatus_U_lower,
            // tb_station_parameter.FloatingbytegeStatus_U_upper,
            // tb_station_parameter.FloatingbytegeStatus_U_lower,
            // tb_station_parameter.DisbytegeStatus_U_upper,
            // tb_station_parameter.DisbytegeStatus_U_lower
                $query = Yii::app()->bms->createCommand()
                            ->select('*')
                            ->from('my_battery_info')
                            ->where("floor({$sn_key}/10000) = floor(sid/10000)")
                            ->limit(1)
                            ->queryAll();
                if($query){
                    foreach($query[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }

                $query = Yii::app()->bms->createCommand()
                            ->select('bytegeStatus_U_upper,bytegeStatus_U_lower,FloatingbytegeStatus_U_upper,FloatingbytegeStatus_U_lower,DisbytegeStatus_U_upper,DisbytegeStatus_U_lower')
                            ->from('tb_station_parameter')
                            ->where("floor({$sn_key}/10000) = floor(station_sn_key/10000)")
                            ->limit(1)
                            ->queryAll();
                if($query){
                    foreach($query[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }

                $query = Yii::app()->bms->createCommand("
                    select * from (
                        SELECT 
                        ({$value["U"]}-AVG(U))/AVG(U) as cau, 
                        ({$value['T']}-AVG(T))/AVG(T) as cat, 
                        ({$value['R']}-AVG(R))/AVG(R) as car,
                        FLOOR(sn_key/10000) as a_sn 
                        FROM tb_battery_module_history 
                        where record_time='{$value['record_time']}'
                        GROUP BY FLOOR(sn_key/10000)
                        )
                         as a where a.a_sn = FLOOR({$sn_key}/10000) limit 1")
                            ->queryAll();
                if($query){
                    foreach($query[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }

                // (b.U-a.au)/a.au as cau,
                // (b.T-a.at)/a.at as cat,
                // (b.R-a.ar)/a.ar as car
                // left join (SELECT AVG(U) as au, avg(T) as at, avg(R) as ar,FLOOR(sn_key/10000) as a_sn FROM tb_battery_module GROUP BY FLOOR(sn_key/10000)) as a on a.a_sn = FLOOR(b.sn_key/10000)


                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无电池数据！'
            );
        }
        /*
        { "data": "site_name",title:"站名",width:120 },
        { "data": "sid",title:"站号",width:50 },
        { "data": "gid",title:"组号",width:50 },
        { "data": "bid",title:"电池号",width:80  }
        { "data": "U",title:"电池电压（V）",width:150 },
        { "data": "T",title:"电极温度（℃）",width:150 },
        { "data": "R",title:"电池内阻（MΩ）",width:150 },
        { "data": "cau",title:"电压偏离（组均值）度%",width:150, render: function(data){return Math.abs(data*100).toFixed(2)} },
        { "data": "cat",title:"温度偏离（组均值）度%",width:150, render: function(data){return Math.abs(data*100).toFixed(2)} },
        { "data": "car",title:"内阻偏离（组均值）度%",width:150, render: function(data){return Math.abs(data*100).toFixed(2)} },
        { "data": "",title:"预估容量（%）",width:150 },// 错误
        { "data": "",title:"电池寿命（%）",width:150 },// 错误
        { "data": "record_time",title:"时间",width:150 },
        { "data": "FloatingbytegeStatus_U_upper",title:"浮充态电压上限",width:80 },
        { "data": "bytegeStatus_U_upper",title:"充电状态电压上限",width:80 },// 错误
        { "data": "DisbytegeStatus_U_upper",title:"放电态电压上限",width:80 },// 错误
        { "data": "FloatingbytegeStatus_U_lower",title:"浮充态电压下限",width:80 },
        { "data": "bytegeStatus_U_lower",title:"充电态电压下限",width:80 },// 错误
        { "data": "DisbytegeStatus_U_lower",title:"放电态电压下限",width:80 },
        { "data": "BatteryU_H",title:"温度上限（A）",width:150 },
        { "data": "BaterryU_L",title:"温度下限",width:150 },
        { "data": "Rin_High_Limit",title:"内阻上限",width:150 },
       
        */
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
                ->setCellValue('E1', '电池电压（V）')
                ->setCellValue('F1', '电极温度（℃）')
                ->setCellValue('G1', '电池内阻（MΩ）')
                ->setCellValue('H1', '电压偏离（组均值）度%')
                ->setCellValue('I1', '温度偏离（组均值）度%')
                ->setCellValue('J1', '内阻偏离（组均值）度%')
                ->setCellValue('K1', '预估容量（%）')
                ->setCellValue('L1', '电池寿命（%）')
                ->setCellValue('M1', '时间')
                ->setCellValue('N1', '浮充态电压上限')
                ->setCellValue('O1', '充电状态电压上限')
                ->setCellValue('P1', '放电态电压上限')
                ->setCellValue('Q1', '浮充态电压下限')
                ->setCellValue('R1', '充电态电压下限')
                ->setCellValue('S1', '放电态电压下限')
                ->setCellValue('T1', '温度上限（A）')
                ->setCellValue('U1', '温度下限')
                ->setCellValue('V1', '内阻上限');
            $index = 1;
            foreach ($ret['data']['list'] as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, $v['site_name'])
                    ->setCellValue('B'.$index, $v['sid'])
                    ->setCellValue('C'.$index, $v['gid'])
                    ->setCellValue('D'.$index, $v['bid'])
                    ->setCellValue('E'.$index, $v['U'])
                    ->setCellValue('F'.$index, $v['T'])
                    ->setCellValue('G'.$index, $v['R'])
                    ->setCellValue('H'.$index, round(abs($v['cau']*100),2))
                    ->setCellValue('I'.$index, round(abs($v['cat']*100),2))
                    ->setCellValue('J'.$index, round(abs($v['car']*100),2))
                    ->setCellValue('K'.$index, "")
                    ->setCellValue('L'.$index, "")
                    ->setCellValue('M'.$index, $v['record_time'])
                    ->setCellValue('N'.$index, $v['FloatingbytegeStatus_U_upper'])
                    ->setCellValue('O'.$index, $v['bytegeStatus_U_upper'])
                    ->setCellValue('P'.$index, $v['DisbytegeStatus_U_upper'])
                    ->setCellValue('Q'.$index, $v['FloatingbytegeStatus_U_lower'])
                    ->setCellValue('R'.$index, $v['bytegeStatus_U_lower'])
                    ->setCellValue('S'.$index, $v['DisbytegeStatus_U_lower'])
                    ->setCellValue('T'.$index, $v['BatteryU_H'])
                    ->setCellValue('U'.$index, $v['BaterryU_L'])
                    ->setCellValue('V'.$index, $v['Rin_High_Limit']);
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('电池数据查询');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="query_battery.xls"');
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
    // 电池实时数据折线图
    public function actionBatterychart()
    {
        $field = Yii::app()->request->getParam('field','U');
        $this->setPageCount();
        $id = Yii::app()->request->getParam('id',0);
        
        //xl
        $sites = array();
        $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        if ($id) {
            if (is_numeric($id)) {
                if(!empty($sns)){
                    $sql = "select b.{$field}, b.mid, b.sn_key from tb_battery_module_history as b, my_site a ";
                    $sql .= " where b.sn_key in(" . $id . ')';
                    $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                    $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;

                    $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                }elseif($sns === false){
                    $sites = Yii::app()->bms->createCommand()
                        ->select($field.',mid,sn_key')
                        ->from('{{battery_module_history}}')
                        ->where('sn_key in('.$id.')')
                        ->limit($this->count)
                        ->offset(($this->page-1)*$this->count)
                        ->order('record_time desc')
                        ->queryAll();
                }
            }else{
                $arr = explode(',',$id);
                if(!empty($sns)){
                    $sql = "select b.{$field}, b.mid, b.sn_key from tb_battery_module_history as b, my_site a ";
                    $sql .= " where b.sn_key in(" . $id . ')';
                    $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                    $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . count($arr);

                    $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                }elseif($sns === false){
                    $sites = Yii::app()->bms->createCommand()
                        ->select($field.',mid,sn_key')
                        ->from('{{battery_module_history}}')
                        ->where('sn_key in('.$id.')')
                        ->limit(count($arr))
                        ->offset(($this->page-1)*$this->count)
                        ->order('record_time desc')
                        ->queryAll();
                }
            }

        }else{
            if(!empty($sns)){
                $sql = "select b.{$field}, b.mid, b.sn_key from tb_battery_module_history as b, my_site a ";
                $sql .= " where 1=1 ";
                $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;

                $sites = Yii::app()->bms->createCommand($sql)->queryAll();
            }elseif($sns === false){
                $sites = Yii::app()->bms->createCommand()
                    ->select($field.',mid,sn_key')
                    ->from('{{battery_module_history}}')
                    ->limit($this->count)
                    ->offset(($this->page-1)*$this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }
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