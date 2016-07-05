<?php

class RealtimeController extends Controller
{
    //站点实时数据
	public function actionIndex()
	{
        $this->setPageCount();
        $id = Yii::app()->request->getParam('id',0);

        if ($id) {
            $arr = explode(',',$id);
            $temp = array();
            foreach ($arr as $key => $value) {
                $temp[] = $value."0000";
            }
            $id =  implode(',',$temp);
            $sql = "select 
            tb_station_module.*,
            groupmodule.total,
            batterymodule.batteryCount,
            my_site.aid,
            my_site.battery_status, 
            my_site.inductor_type,my_site.site_name,
            my_ups_info.ups_max_charge,
            my_ups_info.ups_max_discharge,
            my_ups_info.ups_maintain_date,
            bb.BBbCharge+bb.BCbDisCharge as charges
            from tb_station_module  
            left join my_site 
                on my_site.serial_number=tb_station_module.sn_key 
            left join (SELECT FLOOR(sn_key/1000)*1000 as sn_key, 
                COUNT(FLOOR(sn_key/1000)) as total 
                FROM tb_group_module GROUP BY FLOOR(sn_key/1000)) as groupmodule 
                on groupmodule.sn_key=tb_station_module.sn_key 
            left join (SELECT FLOOR(sn_key/1000)*1000 as sn_key, COUNT(FLOOR(sn_key/1000)) as batteryCount 
                FROM tb_battery_module GROUP BY FLOOR(sn_key/1000)) as batterymodule 
                on batterymodule.sn_key = tb_station_module.sn_key 
            left join my_ups_info 
                on my_ups_info.sid = tb_station_module.sn_key
            LEFT JOIN (SELECT BBbCharge,BCbDisCharge,FLOOR(sn_key/1000)*1000 AS b_key 
                FROM tb_battery_module GROUP BY FLOOR(sn_key/1000)*1000) AS bb 
                ON bb.b_key = tb_station_module.sn_key
            where tb_station_module.sn_key in (".$id.")";

            $sites = Yii::app()->bms->createCommand($sql)->queryAll();

            //left join (select BBbCharge+BCbDisCharge as charges,sn_key,record_time from tb_battery_module_history where BBbCharge+BCbDisCharge=1 and 10000*floor(sn_key/10000) in (".$id.") order by record_time desc ) as lastDisCharge
                //on floor(lastDisCharge.sn_key/10000) = tb_station_module.sn_key/10000
                //->select('*')
                //->from('{{station_module}}')
                //->where('sn_key in('.$id.')')
                //->limit($this->count)
                //->offset(($this->page - 1) * $this->count)
                //->order('record_time desc')
                //->queryAll();
        }else{
            //$sql = "select tb_station_module.*,groupmodule.total,my_site.battery_status, my_site.inductor_type,my_site.site_name from tb_station_module  left join my_site on my_site.serial_number=tb_station_module.sn_key left join (SELECT FLOOR(sn_key/1000)*1000 as sn_key, COUNT(FLOOR(sn_key/1000)) as total FROM tb_group_module GROUP BY FLOOR(sn_key/1000)) as groupmodule on groupmodule.sn_key=tb_station_module.sn_key";
            $sql = "select tb_station_module.*,groupmodule.total,batterymodule.batteryCount,my_site.battery_status, my_site.inductor_type,my_site.site_name,my_site.aid from tb_station_module  left join my_site on my_site.serial_number=tb_station_module.sn_key left join (SELECT FLOOR(sn_key/1000)*1000 as sn_key, COUNT(FLOOR(sn_key/1000)) as total FROM tb_group_module GROUP BY FLOOR(sn_key/1000)) as groupmodule on groupmodule.sn_key=tb_station_module.sn_key left join (SELECT FLOOR(sn_key/1000)*1000 as sn_key, COUNT(FLOOR(sn_key/1000)) as batteryCount FROM tb_battery_module GROUP BY FLOOR(sn_key/1000)) as batterymodule on batterymodule.sn_key = tb_station_module.sn_key";
            $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                //->select('*')
                //->from('{{station_module}}')
                //->limit($this->count)
                //->offset(($this->page-1)*$this->count)
                //->order('record_time desc')
                //->queryAll();
        }

        //观察员进行地域过滤 xl
        $sites = GeneralLogic::filterDataByAid($_SESSION['uid'], $sites);

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

        echo json_encode($ret);
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
                $sitearr[$value['serial_number']] = $value;
            }

        }
        
        $sites = array();
        //xl
        //通过sql直接选择地域进行过滤
        $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
        if ($id) {
            //$arr = explode(',',$id);
            //$temp = array();
            //foreach ($arr as $key => $value) {
            //    $temp[] = $value.'0000';
            //}
            //$id =  implode(',',$temp);
            if (is_numeric($id)) {
                //xl
                //通过sql直接选择地域进行过滤
                if(!empty($sns)){
                    $sql = "select b.{$field}, b.sn_key,b.sid from tb_station_module_history as b, my_site a ";
                    $sql .= " where b.sn_key in(" . $id . ')';
                    $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                    $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;
                    $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                }elseif($sns === false){
                    $sites = Yii::app()->bms->createCommand()
                        ->select($field . ',sid,sn_key')
                        ->from('{{station_module_history}}')
                        ->where('sn_key in(' . $id . ')')
                        ->limit($this->count)
                        ->offset(($this->page - 1) * $this->count)
                        ->order('record_time desc')
                        ->queryAll();
                }
            }else{
                $arr = explode(',',$id);
                
                //xl
                //通过sql直接选择地域进行过滤
                if(!empty($sns)){
                    $sql = "select distinct b.sid, b.{$field},b.sn_key from tb_station_module as b, my_site a ";
                    $sql .= " where b.sn_key in(" . $id . ')';
                    $sql .= " and FLOOR(b.sn_key/1000) = FLOOR(a.serial_number/1000)";
                    $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . count($arr);
                    $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                }elseif($sns === false){
                    $sites = Yii::app()->bms->createCommand()
                        ->selectDistinct('sn_key,sid,'.$field)
                        ->from('{{station_module}}')
                        ->where('sn_key in('.$id.')')
                        //->limit($this->count)
                        ->limit(count($arr))
                        ->offset(($this->page - 1) * $this->count)
                        ->order('record_time desc')
                        ->queryAll();
                }
            }

        }else{
            //xl
            //通过sql直接选择地域进行过滤
            if(!empty($sns)){
                $sql = "select distinct b.sid, b.{$field},b.sn_key from tb_station_module as b, my_site a ";
                $sql .= " where 1=1 ";
                $sql .= " and FLOOR(b.sn_key/10000) = FLOOR(a.serial_number/10000)";
                $sql .= "and a.serial_number in (" . implode(",", $sns) .") order by b.record_time desc limit " .($this->page - 1) * $this->count. "," . $this->count;
                $sites = Yii::app()->bms->createCommand($sql)->queryAll();
            }elseif($sns === false){
                $sites = Yii::app()->bms->createCommand()
                    ->select($field.',sid,sn_key')
                    ->from('{{station_module}}')
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

                if (isset($sitearr[$value['sn_key']])) {
                    $sitename = $sitearr[$value['sn_key']]['site_name'];
                }else{
                    $sitename = '未知';
                }
                $row['name'] = $sitename;
                // 这个待定
                $row['status'] = 0;
                $row['id'] = $value['sn_key'];

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
        $offset = ($this->page-1)*$this->count;
        $sql = "
        select my_site.site_name, my_site.aid, g.* from tb_group_module as g
        left join my_site on my_site.serial_number/10000 = floor(g.sn_key/10000)
        
        ";
        if ($id) {

            $arr = explode(',',$id);
            $temp = array();
            foreach ($arr as $key => $value) {
                $temp[] = $value.'00';
            }
            $id =  implode(',',$temp);

            $sql .= ' where g.sn_key in ('.$id.')';
        }
        $sql .= "limit $offset, $this->count ";
        $sites = Yii::app()->bms->createCommand($sql)->queryAll();

        //观察员进行地域过滤 xl
        $sites = GeneralLogic::filterDataByAid($_SESSION['uid'], $sites);

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
                    ->select($field.',gid,sn_key,sid')
                    ->from('{{group_module_history}}')
                    ->where('sn_key in('.$id.')')
                    //->limit($this->count)
                    //->offset(($this->page - 1) * $this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }else{
                $arr = explode(',',$id);

                $sites = Yii::app()->bms->createCommand()
                    ->selectDistinct($field.',gid,sn_key,sid')
                    ->from('{{group_module}}')
                    ->where('sn_key in('.$id.')')
                    //->limit(count($arr))
                    //->offset(($this->page - 1) * $this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }

        }else{
            $sites = Yii::app()->bms->createCommand()
                ->select($field.',gid,sn_key,sid')
                ->from('{{group_module}}')
                //->limit($this->count)
                //->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        }
        // var_dump($sites);
        // Yii::app()->end();
        //观察员进行地域过滤 xl
        $sites = GeneralLogic::filterDataBySn($_SESSION['uid'], $sites);

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
                $row['sn_key'] = $value['sid'].'站-组'.$value['gid'];
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
        $sql = "
            select 
            my_site.site_name,
            my_site.aid,
            tb_battery_parameter.*,
            my_battery_info.*,
            my_ups_info.*,
            b.*,
            a.*,
            tb_station_parameter.bytegeStatus_U_upper,
            tb_station_parameter.bytegeStatus_U_lower,
            tb_station_parameter.FloatingbytegeStatus_U_upper,
            tb_station_parameter.FloatingbytegeStatus_U_lower,
            tb_station_parameter.DisbytegeStatus_U_upper,
            tb_station_parameter.DisbytegeStatus_U_lower,
            (b.U-a.au)/a.au as cau,
            (b.T-a.at)/a.at as cat,
            (b.R-a.ar)/a.ar as car
            from tb_battery_module as b
            left join my_site on my_site.serial_number/10000 = FLOOR(b.sn_key/10000)
            left join my_ups_info on my_ups_info.sid/10000 = FLOOR(b.sn_key/10000)
            left join my_battery_info on my_battery_info.sid/10000 = FLOOR(b.sn_key/10000)
            left join tb_battery_parameter on tb_battery_parameter.battery_sn_key=b.sn_key
            left join (SELECT AVG(U) as au, avg(T) as at, avg(R) as ar,FLOOR(sn_key/10000) as a_sn FROM tb_battery_module GROUP BY FLOOR(sn_key/10000)) as a on a.a_sn = FLOOR(b.sn_key/10000)
            left join tb_station_parameter on tb_station_parameter.station_sn_key/10000 = FLOOR(b.sn_key/10000)
        ";
        if ($id) {
            $sql .= " where sn_key in (".$id.")";
            // $sites = Yii::app()->bms->createCommand()
            //     ->select('*')
            //     ->from('{{battery_module}}')
            //     ->where('sn_key in('.$id.')')
            //     //->limit($this->count)
            //     //->offset(($this->page-1)*$this->count)
            //     ->order('gid asc, record_time desc')
            //     //->order('record_time desc')
            //     ->queryAll();
            $sites = Yii::app()->bms->createCommand($sql)->queryAll();
            
            //观察员进行地域过滤 xl
            $sites = GeneralLogic::filterDataByAid($_SESSION['uid'], $sites);
            
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
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无电池数据！'
            );
            echo json_encode($ret);
        }
        
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
                    ->select($field.',mid,sn_key,gid,record_time')
                    ->from('{{battery_module_history}}')
                    ->where('sn_key in('.$id.')')
                    //->limit($this->count)
                    //->offset(($this->page-1)*$this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }else{
                $arr = explode(',',$id);
                $sites = Yii::app()->bms->createCommand()
                    ->selectDistinct($field.',mid,sn_key,gid,record_time')
                    ->from('{{battery_module}}')
                    ->where('sn_key in('.$id.')')
                    //->limit(count($arr))
                    //->offset(($this->page-1)*$this->count)
                    ->order('record_time desc')
                    ->queryAll();
            }

        }else{
            $sites = Yii::app()->bms->createCommand()
                ->select($field.',mid,sn_key,gid,record_time')
                ->from('{{battery_module}}')
                //->limit($this->count)
                //->offset(($this->page-1)*$this->count)
                ->order('record_time desc')
                ->queryAll();
        }
     
        //观察员进行地域过滤 xl
        $sites = GeneralLogic::filterDataBySn($_SESSION['uid'], $sites);

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
                $row['sn_key'] = '组'.$value['gid'].'-电池'.$value['mid'];
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
        $total = 0;
        if ($id != 0) {
            $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{general_alarm}}')
                ->where('equipment_sn in ('.$id.')')
                // ->limit($this->count)
                // ->offset(($this->page - 1) * $this->count)
                ->order('alarm_occur_time desc')
                ->queryAll();

        }else{
            $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('{{general_alarm}}')
                // ->limit($this->count)
                // ->offset(($this->page-1)*$this->count)
                ->order('alarm_occur_time desc')
                ->queryAll();
           
        }
        $total = Yii::app()->bms->createCommand()
                ->select("count(*) as total")
                ->from('{{general_alarm}}')
                ->queryAll();
        // var_dump($total[0]['total']);
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;
            $ret['data']['total'] = $total[0]['total'];
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
                ->from('{{general_alarm}}')
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
                ->from('{{general_alarm}}')
                ->where('alarm_sn='.$alarm_sn)
                //->limit($this->count)
                //->offset(($this->page-1)*$this->count)
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
                //->limit($this->count)
                //->offset(($this->page - 1) * $this->count)
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
                //->limit($this->count)
                //->offset(($this->page - 1) * $this->count)
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
                //->limit($this->count)
                //->offset(($this->page-1)*$this->count)
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