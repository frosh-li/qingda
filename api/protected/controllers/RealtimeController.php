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
            my_ups_info.ups_power

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
            where tb_station_module.sn_key in (".$id.")
            order by my_site.aid asc "
            ;

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
            // $sql = "select tb_station_module.*,groupmodule.total,batterymodule.batteryCount,my_site.battery_status, my_site.inductor_type,my_site.site_name,my_site.aid from tb_station_module  left join my_site on my_site.serial_number=tb_station_module.sn_key left join (SELECT FLOOR(sn_key/1000)*1000 as sn_key, COUNT(FLOOR(sn_key/1000)) as total FROM tb_group_module GROUP BY FLOOR(sn_key/1000)) as groupmodule on groupmodule.sn_key=tb_station_module.sn_key left join (SELECT FLOOR(sn_key/1000)*1000 as sn_key, COUNT(FLOOR(sn_key/1000)) as batteryCount FROM tb_battery_module GROUP BY FLOOR(sn_key/1000)) as batterymodule on batterymodule.sn_key = tb_station_module.sn_key";
            // $sites = Yii::app()->bms->createCommand($sql)->queryAll();
                //->select('*')
                //->from('{{station_module}}')
                //->limit($this->count)
                //->offset(($this->page-1)*$this->count)
                //->order('record_time desc')
                //->queryAll();
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点数据！'
            );
            echo json_encode($ret);
            Yii::app()->end();
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
                $sql = "select * from tb_station_param where sn_key=".$value['sn_key'];
                    $data = Yii::app()->bms->createCommand($sql)->queryRow();
                    $value = array_merge($value, $data);
                $sql = "select record_time as end_time from tb_station_module_history where ChaState=2 and  sn_key=".$value['sn_key']." order by record_time desc limit 0,1";
                $end_time = Yii::app()->bms->createCommand($sql)->queryScalar();
                $sql = "select record_time as start_time from tb_station_module_history where ChaState!=2  and sn_key=".$value['sn_key']." and record_time < '".$end_time."' order by record_time desc limit 0,1";
                $start_time = Yii::app()->bms->createCommand($sql)->queryScalar();
                $value['end_time'] = $end_time;
                $value['start_time'] = $start_time;
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
        //if ($id) {

            $arr = explode(',',$id);
            $temp = array();
            foreach ($arr as $key => $value) {
                $temp[] = $value.'00';
            }
            $id =  implode(',',$temp);

            $sql .= ' where g.sn_key in ('.$id.')';
        //}
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

                $sql = "select * from tb_group_param where floor(sn_key/10000)=".floor($value['sn_key']/10000);
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

            my_battery_info.*,
            my_ups_info.*,
            b.*

            from tb_battery_module as b

            left join my_site on my_site.serial_number/10000 = FLOOR(b.sn_key/10000)
            left join my_ups_info on my_ups_info.sid/10000 = FLOOR(b.sn_key/10000)
            left join my_battery_info on my_battery_info.sid/10000 = FLOOR(b.sn_key/10000)
        ";
        if ($id) {
            $sql .= " where sn_key in (".$id.")";
            $sql .= " order by gid asc, b.bid asc";
            // $sites = Yii::app()->bms->createCommand()
            //     ->select('*')
            //     ->from('{{battery_module}}')
            //     ->where('sn_key in('.$id.')')
            //     //->limit($this->count)
            //     //->offset(($this->page-1)*$this->count)
            //     ->order('gid asc, record_time desc')
            //     //->order('record_time desc')
            //     ->queryAll();
            // $sql .= " ordre by b.id asc";
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

                    $sql = "select * from tb_battery_param where floor(sn_key/10000)=".floor($value['sn_key']/10000);
                    $data = Yii::app()->bms->createCommand($sql)->queryRow();
                    if(empty($data)){
                        $data = array();
                    }
                    $value = array_merge($value, $data);

                    $sql = "select * from tb_station_param where floor(sn_key/10000)=".floor($value['sn_key']/10000);
                    $data = Yii::app()->bms->createCommand($sql)->queryRow();
                    if(empty($data)){
                        $data = array();
                    }
                    $value = array_merge($value, $data);

                    $sql = "select * from tb_battery_param where floor(sn_key/10000)=".floor($value['sn_key']/10000);
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
        $page = Yii::app()->request->getParam('page',1);
        $this->setPageCount(15);
        $total = 0;
        if ($id != 0) {
            $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('my_alerts')
                ->where('status = 0')
                ->limit(15)
                ->offset(($page - 1) * 15)
                ->order('time desc')
                ->queryAll();

        }else{
            $sites = Yii::app()->bms->createCommand()
                ->select('*')
                ->from('my_alerts')
                ->where('status = 0')
                ->limit(15)
                ->offset(($page-1)*15)
                ->order('time desc')
                ->queryAll();

        }
        $total = Yii::app()->bms->createCommand()
                ->select("count(*) as total")
                ->from('my_alerts')
                ->where('status = 0')
                ->queryAll();
        // var_dump($total[0]['total']);
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = 15;
            $ret['data']['total'] = $total[0]['total'];
            foreach($sites as $key=>$value){
                $addinfo = Yii::app()->bms
                    ->createCommand("select `desc`,en,`limit`,suggest,send_msg,send_email,tips,`type` from my_station_alert_desc where en='".$value['code']."' and type='".$value['type']."'")
                    ->queryAll();
                // $value = $addinfo[0];
                $sql = "select site_name,sid from my_site where serial_number=".(FLOOR($value['sn_key']/10000)*10000);
                //var_dump($sql);
                // Yii::app()->end();

                $siteName = Yii::app()->bms
                    ->createCommand($sql)->queryAll();
                //var_dump($siteName);
                $ret['data']['list'][] = array_merge($value,$addinfo[0],$siteName[0]);
            }
            $psql = "select count(*) as count,right(code,1) as atype from my_alerts group by right(code,1)";
            $alertType = Yii::app()->bms->createCommand($psql)->queryAll();
            $ret['data']['types'] = $alertType;
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