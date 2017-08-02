<?php

class QueryController extends Controller
{
    public static function getStationIds($zero = '0000'){
        $id = Yii::app()->request->getParam('id',0);
        if(!$id){
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点数据！'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }
        $arr = explode(',',$id);
        $temp = array();

        foreach ($arr as $key => $value) {
            $temp[] = $value.$zero;
        }

        $id =  implode(',',$temp);
        return $temp;
    }
    //站点实时数据
	public function actionIndex()
	{
        $this->setPageCount(20);
        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));
        $id = Yii::app()->request->getParam('id',0);
        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');
        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', substr($start,0,10));
            $where .= ' and record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', substr($end,0,10));
            $where .= ' and record_time <= "'.$end.'"';
        }

        //xl
        $sites = array();
        $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        if(!$id){
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点数据！'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }
        $arr = explode(',',$id);
        $temp = array();
        foreach ($arr as $key => $value) {
            $temp[] = $value."0000";
        }
        $id =  implode(',',$temp);
        if(!empty($sns)){
            $sql = "select b.* from tb_station_module_history as b, my_site a ";
            $sql .= " where {$where}";
            $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
            $sql .= "and a.serial_number in (" . implode(",", array_intersect($sns,$temp)) .") order by b.record_time desc ";
            $totalquery = "select count(*) as totals from (".$sql.") as c";
            $totals = Yii::app()->bms->createCommand($totalquery)->queryScalar();
            if ($isDownload != 1){
                $sql .= "limit " .($this->page - 1) * $this->count. "," . $this->count;
            }else{
                $sql .= " limit 0, 5000";
            }
            $sites = Yii::app()->bms->createCommand($sql)->queryAll();
        }else{
            $where .= " and sn_key in (".$id.")";
            $sql = "select * from tb_station_module_history";
            $offset = ($this->page-1)*$this->count;
            $sql .= " where ".$where;
            $sql .= " order by record_time desc ";
            $totalquery = "select count(*) as totals from (".$sql.") as c";
            $totals = Yii::app()->bms->createCommand($totalquery)->queryScalar();
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
            $ret['data']['totals'] = intval($totals);
            foreach($sites as $key=>$value){
                // 获取site_name
                $sql = "select site_name from my_site where serial_number={$value['sn_key']}";
                $joinData = Yii::app()->bms->createCommand($sql)->queryScalar();
                $value['site_name'] = $joinData;


                //     my_ups_info.ups_max_charge,
                //     my_ups_info.ups_max_discharge,
                //     my_ups_info.ups_maintain_date,
                $sql = "select ups_max_charge,ups_max_discharge,ups_maintain_date,ups_power from my_ups_info where sid={$value['sn_key']} limit 1";
                $joinData = Yii::app()->bms->createCommand($sql)->queryAll();
                if($joinData){
                    foreach($joinData[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }
                $sql = "select * from tb_station_param where sn_key=".$value['sn_key'];
                    $data = Yii::app()->bms->createCommand($sql)->queryRow();
                    if($data){
                        $value = array_merge($value, $data);
                    }
 
                // $sql = "select record_time as end_time from tb_station_module_history where ChaState=2 and  sn_key=".$value['sn_key']." order by record_time desc limit 0,1";
                // $end_time = Yii::app()->bms->createCommand($sql)->queryScalar();
                // $sql = "select record_time as start_time from tb_station_module_history where ChaState!=2  and sn_key=".$value['sn_key']." and record_time < '".$end_time."' order by record_time desc limit 0,1";
                // $start_time = Yii::app()->bms->createCommand($sql)->queryScalar();
                $value['end_time'] = '';
                $value['start_time'] = '';
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
            $workSheet->setCellValue('A1', '站名')
                ->setCellValue('C1', '电流A')
                ->setCellValue('D1', '电压V')
                ->setCellValue('E1', '温度℃')
                ->setCellValue('F1', '湿度%')
                ->setCellValue('G1', '寿命%')
                ->setCellValue('H1', '预估容量%')
                ->setCellValue('I1', 'UPS状态')
                ->setCellValue('J1', '时间')
                ->setCellValue('K1', '组数')
                ->setCellValue('L1', '电池数')
                ->setCellValue('M1', '功率W/h')
                ->setCellValue('N1', '维护日期')
                ->setCellValue('O1', '放电开始')
                ->setCellValue('P1', '放电结束')
                ->setCellValue('Q1', '温度上限℃')
                ->setCellValue('R1', '温度下限℃')
                ->setCellValue('S1', '湿度上限%')
                ->setCellValue('T1', '湿度下限%')
                ->setCellValue('U1', '最大放电电流A')
                ->setCellValue('V1', '最大充电电流A');
            $index = 1;
            foreach ($ret['data']['list'] as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, isset($v['site_name']) ? $v['site_name']:"")
                    ->setCellValue('B'.$index, isset($v['sid']) ? $v['sid']:"")
                    ->setCellValue('C'.$index, isset($v['Current']) ? $v['Current']:"")
                    ->setCellValue('D'.$index, isset($v['Voltage']) ? $v['Voltage']:"")
                    ->setCellValue('E'.$index, isset($v['Temperature']) ? $v['Temperature']:"")
                    ->setCellValue('F'.$index, isset($v['Humidity']) ? $v['Humidity']:"")
                    ->setCellValue('G'.$index, isset($v['Lifetime']) ? $v['Lifetime']:"")
                    ->setCellValue('H'.$index, isset($v['Capacity']) ? $v['Capacity']:"")
                    ->setCellValue('I'.$index, $v['ChaState'] == 1 ? "充电":($v['ChaState']==2?"放电":"浮冲"))
                    ->setCellValue('J'.$index, isset($v['record_time']) ? $v['record_time']:"")
                    ->setCellValue('K'.$index, isset($v['Groups']) ? $v['Groups']:"")
                    ->setCellValue('L'.$index, isset($v['GroBats']) ? $v['GroBats']:"")
                    ->setCellValue('M'.$index, isset($v['ups_power']) ? $v['ups_power']:"")
                    ->setCellValue('N'.$index, isset($v['ups_maintain_date']) ? $v['ups_maintain_date']:"")
                    ->setCellValue('O'.$index, isset($v['start_time']) ? $v['start_time']:"")
                    ->setCellValue('P'.$index, isset($v['end_time']) ? $v['end_time']:"")
                    ->setCellValue('Q'.$index, isset($v['MaxTem_R']) ? $v['MaxTem_R']:"")
                    ->setCellValue('R'.$index, isset($v['MinTem_R']) ? $v['MinTem_R']:"")
                    ->setCellValue('S'.$index, isset($v['MaxHum_R']) ? $v['MaxHum_R']:"")
                    ->setCellValue('T'.$index, isset($v['MinHum_R']) ? $v['MinHum_R']:"")
                    ->setCellValue('U'.$index, isset($v['ups_max_discharge']) ? $v['ups_max_discharge']:"")
                    ->setCellValue('V'.$index, isset($v['ups_max_charge']) ? $v['ups_max_charge']:"");
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('站历史数据');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            // header("Content-type:application/vnd.ms-excel");
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


    //组实时数据
    public function actionGroupmodule()
    {
        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));
        $this->setPageCount(20);
        $id = Yii::app()->request->getParam('id',0);

        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');
        $where = ' 1 = 1 ';

        if($start){
            $start = date('Y-m-d H:i:s', substr($start, 0,10));
            $where .= ' and record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', substr($end,0,10));
            $where .= ' and record_time <= "'.$end.'"';
        }

        $temp = self::getStationIds('00');
        $where .= " and sn_key in (" . implode(",", $temp) .")";
        // return;
        //xl
        //通过sql直接选择地域进行过滤
           $sites = array();
           // $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
           // if(!empty($sns)){
           //      $sql = "select b.* from tb_group_module_history as b, my_site a ";
           //      $sql .= " where {$where}";
           //      $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
           //      $sql .= "and a.serial_number in (" . implode(",", array_intersect($sns,$temp)) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;
           //      $totalsql = "select count(*) as totals from tb_group_module_history as b, my_site a  where {$where} and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000) and a.serial_number in (" . implode(",", array_intersect($sns,$temp)) .")";
           //      $totals = Yii::app()->bms->createCommand($totalsql)->queryScalar();
           //      $sites = Yii::app()->bms->createCommand($sql)->queryAll();
           //  }elseif($sns === false){

                //$where .= " and sn_key in (".implode(",", $temp).")";

                $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('tb_group_module_history')
                ->where($where)
                ->limit($this->count)
                ->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();

                $totalsql = "select count(*) as totals from tb_group_module_history as b  where {$where}"; 
                $totals = Yii::app()->bms->createCommand($totalsql)->queryScalar();

        //}
        //}

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;
            $ret['data']['totals'] = $totals;
            foreach($sites as $key=>$value){
                $sql = "select * from tb_group_param where floor(sn_key/10000)=".floor($value['sn_key']/10000);
                $data = Yii::app()->bms->createCommand($sql)->queryRow();
                if(empty($data)){
                    $data = array();
                }
                $value = array_merge($value, $data);
                $sql = "select site_name from my_site where floor(serial_number/10000)=".floor($value['sn_key']/10000);
                $data = Yii::app()->bms->createCommand($sql)->queryRow();
                if(empty($data)){
                    $data = array();
                }
                $value = array_merge($value, $data);
                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无组数据！'
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
                ->setCellValue('D1', '电压V')
                ->setCellValue('E1', '电流A')
                ->setCellValue('F1', '平均温度℃')
                ->setCellValue('G1', '湿度%')
                ->setCellValue('H1', '电压均值')
                ->setCellValue('I1', '温度均值')
                ->setCellValue('J1', '内阻均值')
                ->setCellValue('K1', '氢气浓度%')
                ->setCellValue('L1', '氧气浓度%')
                ->setCellValue('M1', '电池数')
                ->setCellValue('N1', '时间')
                ->setCellValue('O1', '最大放电电流A')
                ->setCellValue('P1', '最大充电电流A')
                ->setCellValue('Q1', '氢气上限%')
                ->setCellValue('R1', '氧气上限%')
                ->setCellValue('S1', '温度上限℃')
                ->setCellValue('T1', '温度下限℃')
                ->setCellValue('U1', '湿度上限%')
                ->setCellValue('V1', '湿度下限%');
            $index = 1;
            foreach ($ret['data']['list'] as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, isset($v['site_name']) ? $v['site_name']:"")
                    ->setCellValue('B'.$index, isset($v['sid']) ? $v['sid']:"")
                    ->setCellValue('C'.$index, isset($v['bid']) ? $v['bid']:"")
                    ->setCellValue('D'.$index, isset($v['Voltage']) ? $v['Voltage']:"")
                    ->setCellValue('E'.$index, isset($v['Current']) ? $v['Current']:"")
                    ->setCellValue('F'.$index, isset($v['Temperature']) ? $v['Temperature']:"")
                    ->setCellValue('G'.$index, isset($v['Humidity']) ? $v['Humidity']:"")
                    ->setCellValue('H'.$index, isset($v['Avg_U']) ? $v['Avg_U']:"")
                    ->setCellValue('I'.$index, isset($v['Avg_T']) ? $v['Avg_T']:"")
                    ->setCellValue('J'.$index, isset($v['Avg_R']) ? $v['Avg_R']:"")
                    ->setCellValue('K'.$index,"")
                    ->setCellValue('L'.$index,"")
                    ->setCellValue('M'.$index, isset($v['GroBats']) ? $v['GroBats']:"")
                    ->setCellValue('N'.$index, isset($v['record_time']) ? $v['record_time']:"")
                    ->setCellValue('O'.$index, isset($v['DisChaLim_R']) ? $v['DisChaLim_R']:"")
                    ->setCellValue('P'.$index, isset($v['ChaLim_R']) ? $v['ChaLim_R']:"")
                    ->setCellValue('Q'.$index,"")
                    ->setCellValue('R'.$index,"")
                    ->setCellValue('S'.$index, isset($v['MaxTem_R']) ? $v['MaxTem_R']:"")
                    ->setCellValue('T'.$index, isset($v['MinTem_R']) ? $v['MinTem_R']:"")
                    ->setCellValue('U'.$index, isset($v['MaxHumi_R']) ? $v['MaxHumi_R']:"")
                    ->setCellValue('V'.$index, isset($v['MinHumi_R']) ? $v['MinHumi_R']:"");
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('站历史数据');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            // header("Content-type:application/vnd.ms-excel");
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="group_history.xls"');
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

    // 电池实时数据
    public function actionBatterymodule()
    {
        $this->setPageCount(20);
        $id = Yii::app()->request->getParam('id',0);
        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');
        $isDownload = Yii::app()->request->getParam('isdownload',0);
        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', substr($start, 0,10));
            $where .= ' and b.record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', substr($end,0,10));
            $where .= ' and b.record_time <= "'.$end.'"';
        }

        $temp = self::getStationIds("");
        
        $sql = "
            select b.* from tb_battery_module_history as b
        ";

        $where .= " and sn_key in (" . implode(",", $temp) .")";

        $offset = ($this->page-1)*$this->count;
        $sql .= " where ".$where;

        $sqltotal = "";
        //xl
        //通过sql直接选择地域进行过滤
        // $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        // if(!empty($sns)){
        //     $sql = "select b.*,a.aid from tb_battery_module_history as b, my_site a ";
        //     $sql .= " where ".$where;
        //     $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
        //     $sql .= "and a.serial_number in (" . implode(",", array_intersect($sns,$temp)) .")";
        //     $sqltotal = $sql;
        //     $sql .= " order by b.record_time desc ";

        // }else{
            
            $sql .= " order by b.record_time desc ";
            $sqltotal = 'select count(*) as totals from tb_battery_module_history as b where '.$where;
        //}


        if ($isDownload != 1){
            $sql .= " limit $offset, $this->count ";
        }else{
            $sql .= " limit 0,5000";
        }

        // var_dump($sql);
        // return;

        $sites = Yii::app()->bms->createCommand($sql)
            ->queryAll();
        //}

        $totals = Yii::app()->bms->createCommand($sqltotal)
            ->queryScalar();

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;
            $ret['data']['totals'] = $totals;
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
                            ->from('tb_battery_param')
                            ->where("floor({$sn_key}/10000) = floor(sn_key/10000)")
                            ->limit(1)
                            ->queryAll();
                if($query){
                    foreach($query[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }

                $query = Yii::app()->bms->createCommand()
                            ->select('ups_max_charge,ups_max_discharge,floting_charge,ups_power')
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
                            ->select('battery_voltage,battery_oum,battery_max_current,battery_float_up,battery_float_dow,battery_discharge_down')
                            ->from('my_battery_info')
                            ->where("floor({$sn_key}/10000) = floor(sid/10000)")
                            ->limit(1)
                            ->queryAll();
                if($query){
                    foreach($query[0] as $key=>$val){
                        $value[$key] = $val;
                    }
                }



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


    // 实时报警数据
    public function actionGalarm()
    {
        // 具体流程见 警情判断流程判断逻辑.docx 文档
        // 数据直接出，通过command来处理数据
        $id = Yii::app()->request->getParam('id',0);
        $this->setPageCount();
        $start =Yii::app()->request->getParam('start');
        $end = substr(Yii::app()->request->getParam('end'),0,10);
        $where = ' 1 =1 ';
        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and alarm_occur_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', substr(Yii::app()->request->getParam('end'),0,10));
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


}