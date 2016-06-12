<?php
class ReportController  extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';
    // 电池充放电
    public function actionBettary()
    {
        $alarm_sn = Yii::app()->request->getParam('alarm_sn',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        $sql = "select * from {{general_alarm}} where alarm_sn= ".$alarm_sn;
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            $ret['data'] = $row;
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该报警信息！'
            );
        }
        echo json_encode($ret);
    }
    // 电池使用年限
    public function actionByearlog()
    {
        $id = Yii::app()->request->getParam('id',0);

        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));

        $this->setPageCount();

        if ($isDownload == 1) {
            $limit = 5000;
            $offset = 0;
        }else{
            $limit = $this->count;
            $offset = ($this->page - 1) * $this->count;
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        

        $sql = "select * from {{battery_info}} ";

        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');
        $where = 'where 1=1 ';
        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and battery_date >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and battery_date <= "'.$end.'"';
        }

        $sql .= $where;

        $bettery = Yii::app()->db->createCommand($sql)->queryAll();
        if ($bettery) {
            foreach ($bettery as $index => $item) {
                $b[$item['sid']] = $item;
            }
        }



        if ($id) {
            $sql = "select sid from my_site where serial_number in (".$id.")";
            $sids =  Yii::app()->db->createCommand($sql)->queryColumn();


            $bets = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{battery_module_history}}')
                ->where('sid in ('.implode(',',$sids).')')
                ->limit($limit)
                ->offset($offset)
                ->order('record_time desc')
                ->queryAll();

        }else{
            $bets = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{battery_module_history}}')
                ->limit($limit)
                ->offset($offset)
                ->order('record_time desc')
                ->queryAll();
        }
        if ($bets) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach ($bets as $index => $bet) {
                $ret['data']['list'][] = array(
                    'brand'=>isset($b[$bet['sid']]) ? $b[$bet['sid']]['battery_factory']:'',
                    'battery_date'=>isset($b[$bet['sid']]) ? $b[$bet['sid']]['battery_date']:'',
                    'battery_oum'=>isset($b[$bet['sid']]) ? $b[$bet['sid']]['battery_oum']:'',
                    'battery_voltage'=>isset($b[$bet['sid']]) ?$b[$bet['sid']]['battery_voltage']:'',
                    'battery_scrap_date'=>isset($b[$bet['sid']]) ?$b[$bet['sid']]['battery_scrap_date']:'',
                    'battery_life'=>isset($b[$bet['sid']]) ?$b[$bet['sid']]['battery_life']:'',
                    'battery_install_date'=>isset($b[$bet['sid']]) ? date('Y-m-d H:i:s',$b[$bet['sid']]['create_time']) :'',
                    'R'=>$bet['R'],
                    'U'=>$bet['U'],
                    'sn_key'=>$bet['sn_key'],
                );
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无电池信息！'
            );
        }
        if ($isDownload == 1) {
            Yii::$enableIncludePath = false;
            Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
            $objPHPExcel = new PHPExcel();
            $workSheet = $objPHPExcel->setActiveSheetIndex(0);
            // Add some data
            $workSheet->setCellValue('A1', '品牌')
                ->setCellValue('B1', '生产日期')
                ->setCellValue('C1', '电池安装日期')
                ->setCellValue('D1', '电池的电压')
                ->setCellValue('E1', '出厂标称内阻')
                ->setCellValue('F1', '电池的内阻')
                ->setCellValue('G1', '强制报废日期');
            $index = 1;
            foreach ($ret['data']['list'] as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, isset($v['brand']) ? $v['brand']:"")
                    ->setCellValue('B'.$index, isset($v['battery_date']) ? $v['battery_date']:"")
                    ->setCellValue('C'.$index, isset($v['battery_install_date']) ? $v['battery_install_date']:"")
                    ->setCellValue('D'.$index, isset($v['U']) ? $v['U']:"")
                    ->setCellValue('E'.$index, isset($v['battery_oum']) ? $v['battery_oum']:"")
                    ->setCellValue('F'.$index, isset($v['R']) ? $v['R']:"")
                    ->setCellValue('G'.$index, isset($v['battery_scrap_date']) ? $v['battery_scrap_date']:"");
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('电池使用年限');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="byearlog.xls"');
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
        if ($id != 0) {
            $where .= '  and alarm_para1_name in(' . $id . ') ';
        }
        $where .= ' and alarm_recovered_time >='.$begin . " and alarm_recovered_time <= ".$end;
        $this->setPageCount();
        $alarms = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{general_alarm}}')
            ->where($where)
            ->limit($this->count)
            ->offset(($this->page - 1) * $this->count)
            ->order('alarm_occur_time desc')
            ->queryAll();
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

        echo json_encode($ret);
    }

    //偏离趋势图
    /**
     * 8、偏离趋势报表：
     * 电压均值、电池温度均值、电池内阻均值、异常电池电压、异常电池温度、异常电池内阻 （异常值：取当时异常的实际数值）
     * 电池电压、温度、内阻均值（指每组均值）：计算当前异常组的所有电池数据的平均值得出。
     * 注意：当有电池电压异常、温度异常、内阻异常的 报警时才生成此表记录；
     */
    public function actionDeviationTrend() {
        $id = Yii::app()->request->getParam('id',0);
        
        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));

        $this->setPageCount();

        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');

        $where = 'where 1=1 ';
        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and record_time <= "'.$end.'"';
        }

        

        if ($id != '') {
            $strArray = explode(',',$id);
            $ids = array();
            foreach ($strArray as $k => $v)
            {
                $v = intval($v);
                if ($v >0) {
                    $ids[$v] = $v;
                }
            }

            $where .= '`sid` in ('.implode(',',$ids).')';
        }
        $offset = ($this->page - 1) * $this->count;
        $counts = $this->count;
        $sql = "select * from tb_station_module_history {$where} order by record_time desc limit {$offset},{$counts} ";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        if (empty($result)) {
            $this->ajaxReturn(-1, '暂无数据a');
        }

        $siteArray = array();
        foreach ($result as $rs) {
            $site = $rs['sn_key'];
            // var_dump($site);
            $siteArray[$site] = $site;
        }

        // 获取站点名
        $where = "serial_number in (" . implode(',', $siteArray) . ")";
        $siteInfo =  Yii::app()->db->createCommand()
            ->select('site_name, sid, serial_number')
            ->from('{{site}}')
            ->where($where)
            ->queryAll();

        $siteInfoArray = array();
        foreach ($siteInfo as $site) {
            $siteInfoArray[$site['serial_number']] = $site['site_name'];
        }

        // if (empty($siteInfoArray)) {
        //     $this->ajaxReturn(-1, '暂无数据b');
        // }

        foreach ($result as $k =>$v)
        {
            $result[$k]['site_name'] = isset($siteInfoArray[$v['sn_key']]) ? $siteInfoArray[$v['sn_key']] : '';
        }

        // 获取对应电池的3个主要指标数据TUR

        foreach ($result as $k =>$v)
        {
            $sql = "
                        SELECT 
                        AVG(U) as avgU, 
                        AVG(T) as avgT, 
                        AVG(R) as avgR,
                        FLOOR(sn_key/10000) as a_sn 
                        FROM tb_battery_module_history
                        where record_time='{$v['record_time']}'
                        and floor(sn_key/10000)*10000 = {$v['sn_key']}
                        GROUP BY FLOOR(sn_key/10000)
                        ";
            $bdata =  Yii::app()->db->createCommand($sql)->queryAll();
            if($bdata){
                foreach($bdata[0] as $key=>$val){
                    $result[$k][$key] = $val;
                }
            }
            // $result[$k]['site_name'] = isset($siteInfoArray[$v['sid']]) ? $siteInfoArray[$v['sid']] : '';
        }


        if ($isDownload == 1) {
            Yii::$enableIncludePath = false;
            Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
            $objPHPExcel = new PHPExcel();
            $workSheet = $objPHPExcel->setActiveSheetIndex(0);
            // Add some data
            $workSheet->setCellValue('A1', '序号')
                ->setCellValue('B1', '站点ID')
                ->setCellValue('C1', '站名')
                ->setCellValue('D1', '时间')
                ->setCellValue('E1', '平均电压')
                ->setCellValue('F1', '平均温度')
                ->setCellValue('G1', '平均电阻')
                ->setCellValue('H1', '异常电压')
                ->setCellValue('I1', '异常温度')
                ->setCellValue('J1', '异常电阻');
            $index = 1;
            foreach ($result as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, $index - 1)
                    ->setCellValue('B'.$index, $v['sid'])
                    ->setCellValue('C'.$index, $v['site_name'])
                    ->setCellValue('D'.$index, $v['record_time'])
                    ->setCellValue('E'.$index, $v['avgU'])
                    ->setCellValue('F'.$index, $v['avgT'])
                    ->setCellValue('G'.$index, $v['avgR'])
                    ->setCellValue('H'.$index, abs($v['avgU']-0.3)/0.3*100)
                    ->setCellValue('I'.$index, abs($v['avgT']-3)/3*100)
                    ->setCellValue('J'.$index, abs($v['avgR']-5)/5*100);
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
            $this->ajaxReturn(0, '', $result);
        }
    }

    /**
     * 9、充放电统计报表；目前应该只有某块电池的充放电统计表 没有整个站的字段。
     * 请马总确认这里是针对电池来说的吗？
     * 充放电统计表针对于站来说的；
     * 充放电状态，取站中第一块电池的充放电状态
     * 增加字段：充放电统计列表 增加时间列；
     */
    public function actionChargeOrDischarge() {
        $id = Yii::app()->request->getParam('id',0);
        
        $isDownload = intval(Yii::app()->request->getParam('isdownload', '0'));

        $this->setPageCount();

        $where = '';
        $start =Yii::app()->request->getParam('start');
        $end = Yii::app()->request->getParam('end');

        if($start){
            $start = date('Y-m-d H:i:s', Yii::app()->request->getParam('start'));
            $where .= ' and record_time >= "'.$start.'"';
        }
        if($end){
            $end = date('Y-m-d H:i:s', Yii::app()->request->getParam('end'));
            $where .= ' and record_time <= "'.$end.'"';
        }

        $result = Yii::app()->bms->createCommand()
            ->select('sn_key, record_time, sid')
            ->from('{{station_module_history}}')
            ->where($where)
            ->limit($this->count)
            ->offset(($this->page - 1) * $this->count)
            ->order('record_time, sid')
            ->queryAll();

        if (empty($result)) {
            $this->ajaxReturn(-1, '暂无数据');
        }

        // // 获取站点充放电状态
        // $batteryStatus = Yii::app()->bms->createCommand()
        //     ->select('sn_key, record_time, sid, BBbCharge, BCbDisCharge')
        //     ->from('{{battery_module_history}}')
        //     ->where($where);

        foreach ($result as $mainkey=>$value) {
            $sql = "select sid, site_name from my_site where serial_number={$value['sn_key']} limit 1";
            $joinData = Yii::app()->bms->createCommand($sql)->queryAll();
            if($joinData){
                foreach($joinData[0] as $key=>$val){
                    $result[$mainkey][$key] = $val;
                }
            }

            $sql = "select BBbCharge,BCbDisCharge from tb_battery_module_history where 10000*floor(sn_key/10000) = {$value['sn_key']} limit 1";

            $joinData = Yii::app()->bms->createCommand($sql)->queryAll();
            if($joinData){
                foreach($joinData[0] as $key=>$val){
                    $result[$mainkey][$key] = $val;
                }
            }

        }

        if ($isDownload == 1) {
            Yii::$enableIncludePath = false;
            Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
            $objPHPExcel = new PHPExcel();
            $workSheet = $objPHPExcel->setActiveSheetIndex(0);
            // Add some data
            $workSheet->setCellValue('A1', '序号')
                ->setCellValue('B1', '站点ID')
                ->setCellValue('C1', '站名')
                ->setCellValue('D1', '时间')
                ->setCellValue('E1', '充电状态')
                ->setCellValue('F1', '放电状态');
            $index = 1;
            foreach ($dataArray as $v) {
                $index ++;
                $workSheet->setCellValue('A'.$index, $index - 1)
                    ->setCellValue('B'.$index, $v['sid'])
                    ->setCellValue('C'.$index, $v['name'])
                    ->setCellValue('D'.$index, $v['time'])
                    ->setCellValue('E'.$index, $v['BBbCharge'] ? '是':'否')
                    ->setCellValue('F'.$index, $v['BCbDisCharge'] ? '是':'否');
            }
            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('充放电统计表');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="charge_discharge.xls"');
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
            $this->ajaxReturn(0, '', $result);
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
