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

            $sites = Yii::app()->bms->createCommand()
                ->select('
                    tb_station_module.*,
                    my_site.aid,
                    my_site.sid,
                    my_site.battery_status,
                    my_site.site_name,
                    p.*,
                    my_ups_info.ups_maintain_date,
                    my_ups_info.ups_power,
                    my_ups_info.ups_max_discharge,
                    my_ups_info.ups_max_charge
                    ')
                ->from("tb_station_module")
                ->leftJoin("my_site","my_site.serial_number=tb_station_module.sn_key")
                ->leftJoin("my_ups_info","my_ups_info.sid=tb_station_module.sn_key")
                ->leftJoin('tb_station_param as p','tb_station_module.sn_key=p.sn_key')
                ->where(array("in","tb_station_module.sn_key",$temp))
                ->order("my_site.id asc")
                ->limit($this->count)
                ->offset(($this->page - 1) * $this->count)
                ->queryAll();

            $total = Yii::app()->bms->createCommand()
                ->select('count(*) as totals')
                ->from("tb_station_module")
                ->where(array("in","tb_station_module.sn_key",$temp))
                ->queryScalar();

        }else{
            $ret['response'] = array(
                'code' => 0,
                'msg' => 'ok'
            );
            $ret['data'] = array();
            echo json_encode($ret);
            Yii::app()->end();
        }

        //观察员进行地域过滤 xl
        $sites = GeneralLogic::filterDataByAid($_SESSION['uid'], $sites);
        // $total = GeneralLogic::filterDataByAid($_SESSION['uid'], $total);
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;
            $ret['data']['totals'] = intval($total);
            foreach($sites as $key=>$value){
                // $sql = "select record_time as end_time from tb_station_module_history where ChaState=2 and  sn_key=".$value['sn_key']." order by record_time desc limit 0,1";
                // $end_time = Yii::app()->bms->createCommand($sql)->queryScalar();
                // $sql = "select record_time as start_time from tb_station_module_history where ChaState!=2  and sn_key=".$value['sn_key']." and record_time < '".$end_time."' order by record_time desc limit 0,1";
                // $start_time = Yii::app()->bms->createCommand($sql)->queryScalar();
                // $value['end_time'] = $end_time;
                // $value['start_time'] = $start_time;
                $value['end_time'] = '';
                $value['start_time'] = '';
                $ret['data']['list'][] = $value;
            }

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

            $arr = explode(',',$id);
            $temp = array();
            foreach ($arr as $key => $value) {
                $temp[] = $value.'00';
            }
            $id =  implode(',',$temp);

            $sql .= ' where g.sn_key in ('.$id.')';
        $sql .= " order by my_site.id asc ";
        $sql .= "limit $offset, $this->count ";

        $sites = Yii::app()->bms->createCommand($sql)->queryAll();
        $totals = Yii::app()->bms->createCommand('
            select count(*) as totals from tb_group_module
            where tb_group_module.sn_key in ('.$id.')
            ')->queryScalar();
        //观察员进行地域过滤 xl
        $sites = GeneralLogic::filterDataByAid($_SESSION['uid'], $sites);
        // $totals = GeneralLogic::filterDataByAid($_SESSION['uid'], $totals);
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
                $sql = "select * from tb_group_param where floor(sn_key/10000)=".floor($value['sn_key']/10000);
                $data = Yii::app()->bms->createCommand($sql)->queryRow();
                if(empty($data)){
                    $data = array();
                }
                $value = array_merge($value, $data);
                $ret['data']['list'][] = $value;
            }

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
            $sql .= " order by my_site.serial_number asc, gid asc, b.bid asc";
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

            }

            echo json_encode($ret);
        }else{
            $ret['response'] = array(
                'code' => 0,
                'msg' => 'ok'
            );
            $ret['data'] = array();
            echo json_encode($ret);
        }

    }

    public function actionRemoveIgnores()
    {
        $type = Yii::app()->request->getParam('type',0);
        $code = Yii::app()->request->getParam('code',0);
        $sn_key = Yii::app()->request->getParam('sn_key',0);
        $sql = "delete from my_ignores where type='$type' and code='$code' and sn_key='$sn_key'";
        Yii::app()->db->createCommand($sql)->execute();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $contact = isset($_SESSION['username']) ? $_SESSION['username'] : '未知用户';
        $log = array(
            'type'=>3,
            'uid'=>$this->session->getUid(),
            'username'=>$contact,
            'content'=>$contact."取消忽略警情$sn_key $type $code",
            'oldvalue'=>'',
            'newvalue'=>''
        );
        $this->addlog($log); 
        echo json_encode($ret);
    }

    public function actionIgnores()
    {
        //需要根据my_sysuser的area区域字段来区分数据报警
        //mysite的aid可以关联tree的id
        $areas = "select area from my_sysuser where id = ".$_SESSION["uid"];
        $auths = Yii::app()->db->createCommand($areas)->queryScalar();
        $id = Yii::app()->request->getParam('id',0);
        // echo $auths;
        $sn_key_list = array();
        if ($auths != "*"){
            $sql = "select serial_number from my_site where aid in ($auths)";
            $search = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($search as $value) {
                $sn_key_list[] = $value['serial_number']/10000;
            }
        }
        unset($where,$sql);

        // 具体流程见 警情判断流程判断逻辑.docx 文档
        // 数据直接出，通过command来处理数据

        $start =substr(Yii::app()->request->getParam('start'),0,10);
        $end = substr(Yii::app()->request->getParam('end'),0,10);
        $type = Yii::app()->request->getParam('type',0);
        $cautionType = Yii::app()->request->getParam('cautionType','ALL');


        // print_r($sn_key_list);
        if (count($sn_key_list) > 0 && $id == 0) {
            // print_r($sn_key_list);
            $id = implode(',', $sn_key_list);
        }
        // echo $id;

        //     $temp = array();
        //     foreach ($arr as $key => $value) {
        //         $temp[] = $value."0000";
        //     }
        // }else{
        //     $temp = false;
        // }

        $page = Yii::app()->request->getParam('page',1);
        $this->setPageCount(20);
        $total = 0;
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $where = " 1=1";
        if($id){
            $where .= " and sn_key/10000 in ($id)";
        }
				// 查询出所有的忽略列表
        $ignores = Yii::app()->bms->createCommand()
            ->select('*')
            ->from("my_ignores")
            ->where($where)
            ->limit(20)
            ->offset(($page-1)*20)
            ->queryAll();
        $ignoresCounts = Yii::app()->bms->createCommand()
            ->select("count(*) as total")
            ->from('my_ignores')
            ->where($where)
            ->queryScalar();
				//$ret['data']['list'] = $ignores;
        if ($ignores) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = 20;
            $ret['data']['totals'] = intval($ignoresCounts);
            foreach($ignores as $key=>$value){
                $addinfo = Yii::app()->bms
                    ->createCommand("select `desc` from my_station_alert_desc where en='".$value['code']."' and type='".$value['type']."'")
                    ->queryAll();
                // $value = $addinfo[0];
                // var_dump($value);
                $sql = "select site_name,sid from my_site where serial_number=".(FLOOR($value['sn_key']/10000)*10000);
                //var_dump($sql);
                // Yii::app()->end();

                $siteName = Yii::app()->bms
                    ->createCommand($sql)->queryAll();
                if($siteName && sizeof($siteName) > 0){
                    $ret['data']['list'][] = array_merge($value,$addinfo[0],$siteName[0]);
                }else{
                    $ret['data']['list'][] = array_merge($value,$addinfo[0], array("site_name"=>"未知","sid"=>""));
                }

            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无报警信息！'
            );
        }
        echo json_encode($ret);
    }
    // 实时报警数据
    public function actionGalarm()
    {
        //需要根据my_sysuser的area区域字段来区分数据报警
        //mysite的aid可以关联tree的id
        $areas = "select area from my_sysuser where id = ".$_SESSION["uid"];
        $auths = Yii::app()->db->createCommand($areas)->queryScalar();
        // echo $auths;
        $sn_key_list = array();
        if ($auths != "*"){
            $sql = "select serial_number from my_site where aid in ($auths)";
            $search = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($search as $value) {
                $sn_key_list[] = $value['serial_number']/10000;
            }
        }
        unset($where,$sql);

        // 具体流程见 警情判断流程判断逻辑.docx 文档
        // 数据直接出，通过command来处理数据

        $start =substr(Yii::app()->request->getParam('start'),0,10);
        $end = substr(Yii::app()->request->getParam('end'),0,10);
        $type = Yii::app()->request->getParam('type',0);
        $cautionType = Yii::app()->request->getParam('cautionType','ALL');

        $id = Yii::app()->request->getParam('id',0);

        // print_r($sn_key_list);
        if (count($sn_key_list) > 0 && $id == 0) {
            // print_r($sn_key_list);
            $id = implode(',', $sn_key_list);
        }
        // echo $id;

        //     $temp = array();
        //     foreach ($arr as $key => $value) {
        //         $temp[] = $value."0000";
        //     }
        // }else{
        //     $temp = false;
        // }

        $page = Yii::app()->request->getParam('page',1);
        $this->setPageCount(20);
        $total = 0;
        // if ($id != 0) {
        //     $sites = Yii::app()->bms->createCommand()
        //         ->select('*')
        //         ->from('my_alerts')
        //         ->where('status = 0')
        //         ->limit(15)
        //         ->offset(($page - 1) * 15)
        //         ->order('time desc')
        //         ->queryAll();

        // }else{
        if($type == 0){
            $where = $cautionType == "ALL" ? '(status=0 or status=1)':'(status=0 or status=1) and right(code,1)="'.$cautionType.'"';
            if ($id != 0) $where .= " and floor(sn_key/10000) in ($id)";
            // echo $where;
        }else{
            $where = $cautionType == "ALL" ? '(status <> 0 )':'(status <> 0 ) and right(code,1)="'.$cautionType.'"';
            if ($id != 0) $where .= " and sn_key in ($id)";
            // if($start){
            //     $start = date('Y-m-d H:i:s', substr($start,0,10));
            //     $where .= " and `time` >= '2018-01-16 21:08:00'";
            // }
            // if($end){
            //     $end = date('Y-m-d H:i:s', substr($end,0,10));
            //     $where .= " and `time` <= '$end'";
            // }
            // echo $where;
        }
        $where .= " and not exists (select * from my_ignores where my_ignores.sn_key=floor(my_alerts.sn_key/1000)*1000 and my_ignores.code=my_alerts.`code` and my_ignores.type=my_alerts.type)";
        $sites = Yii::app()->bms->createCommand()
        ->select('*')
        ->from('my_alerts')
        ->where($where)
        ->limit(20)
        ->offset(($page-1)*20)
        ->order('time desc')
        ->queryAll();

        //}
        if($type == 0){
            $total = Yii::app()->bms->createCommand()
                ->select("count(*) as total")
                ->from('my_alerts')
                ->where($where)
                ->queryScalar();
        }else{
            $total = Yii::app()->bms->createCommand()
                ->select("count(*) as total")
                ->from('my_alerts')
                ->where($where)
                ->queryScalar();
        }

        // var_dump($total[0]['total']);
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
				// 查询出所有的忽略列表
				$ignores = Yii::app()->bms->createCommand()->select('*')->from("my_ignores")->queryAll();
				$ret['data']['ignores'] = $ignores;
        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = 20;
            $ret['data']['totals'] = intval($total);
            foreach($sites as $key=>$value){
                $addinfo = Yii::app()->bms
                    ->createCommand("select `desc`,en,suggest,send_msg,send_email,tips,`type` from my_station_alert_desc where en='".$value['code']."' and type='".$value['type']."'")
                    ->queryAll();
                // $value = $addinfo[0];
                $sql = "select site_name,sid from my_site where serial_number=".(FLOOR($value['sn_key']/10000)*10000);
                //var_dump($sql);
                // Yii::app()->end();

                $siteName = Yii::app()->bms
                    ->createCommand($sql)->queryAll();
                if($siteName && sizeof($siteName) > 0){
                    //var_dump($value['code']);
                    //var_dump($value['type']);
                    //var_dump($addinfo[0]);
                    $ret['data']['list'][] = array_merge($value,sizeof($addinfo) > 0 ? $addinfo[0]: array(),$siteName[0]);
                }else{
                    $ret['data']['list'][] = array_merge($value,$addinfo[0], array("site_name"=>"未知","sid"=>""));
                }

            }
            $psql = "select count(*) as count,right(code,1) as atype from my_alerts where status=0 and  not exists (select * from my_ignores where my_ignores.sn_key=floor(my_alerts.sn_key/1000)*1000 and my_ignores.code=my_alerts.`code` and my_ignores.type=my_alerts.type) group by right(code,1) ";
            $alertType = Yii::app()->bms->createCommand($psql)->queryAll();
            $ret['data']['types'] = $alertType;
            $ret['data']['voice_on_off']= Yii::app()->config->get("voice_on_off");
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无报警信息！'
            );
        }

        echo json_encode($ret);
    }

		// 实时报警数据
    public function actionGalarmhistory()
    {
        //需要根据my_sysuser的area区域字段来区分数据报警
        //mysite的aid可以关联tree的id
        $areas = "select area from my_sysuser where id = ".$_SESSION["uid"];
        $auths = Yii::app()->db->createCommand($areas)->queryScalar();
        // echo $auths;
        $sn_key_list = array();
        if ($auths != "*"){
            $sql = "select serial_number from my_site where aid in ($auths)";
            $search = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($search as $value) {
                $sn_key_list[] = $value['serial_number']/10000;
            }
        }
        unset($where,$sql);

        // 具体流程见 警情判断流程判断逻辑.docx 文档
        // 数据直接出，通过command来处理数据

        $start =substr(Yii::app()->request->getParam('start'),0,10);
        $end = substr(Yii::app()->request->getParam('end'),0,10);
        $type = Yii::app()->request->getParam('type',0);
        $cautionType = Yii::app()->request->getParam('cautionType','ALL');

        $id = Yii::app()->request->getParam('id',0);

        // print_r($sn_key_list);
        if (count($sn_key_list) > 0 && $id == 0) {
            // print_r($sn_key_list);
            $id = implode(',', $sn_key_list);
        }

        $page = Yii::app()->request->getParam('page',1);
        $this->setPageCount(20);
        $total = 0;
				$where = "1=1 ";
				if($cautionType != "ALL"){
					$where = 'right(code,1)="'.$cautionType.'"';
				}
        if ($id != 0) {
					$where .= " and sn_key in ($id)";
        }

        $sites = Yii::app()->bms->createCommand()
        ->select('*')
        ->from('my_alerts_history')
        ->where($where)
        ->limit(20)
        ->offset(($page-1)*20)
        ->order('time desc')
        ->queryAll();

        $total = Yii::app()->bms->createCommand()
            ->select("count(*) as total")
            ->from('my_alerts_history')
            ->where($where)
            ->queryScalar();


        // var_dump($total[0]['total']);
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sites) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = 20;
            $ret['data']['totals'] = intval($total);
            foreach($sites as $key=>$value){
                $addinfo = Yii::app()->bms
                    ->createCommand("select `desc`,en,suggest,send_msg,send_email,tips,`type` from my_station_alert_desc where en='".$value['code']."' and type='".$value['type']."'")
                    ->queryAll();
                // $value = $addinfo[0];
                // var_dump($value);
                $sql = "select site_name,sid from my_site where serial_number=".(FLOOR($value['sn_key']/10000)*10000);
                //var_dump($sql);
                // Yii::app()->end();

                $siteName = Yii::app()->bms
                    ->createCommand($sql)->queryAll();
                if($siteName && sizeof($siteName) > 0){
                    $ret['data']['list'][] = array_merge($value,$addinfo[0],$siteName[0]);
                }else{

                    $ret['data']['list'][] = array_merge($value,$addinfo[0], array("site_name"=>"未知","sid"=>""));


                }

            }
            $psql = "select count(*) as count,right(code,1) as atype from my_alerts where status=0 group by right(code,1) ";
            $alertType = Yii::app()->bms->createCommand($psql)->queryAll();
            $ret['data']['types'] = $alertType;
            $ret['data']['voice_on_off']= Yii::app()->config->get("voice_on_off");
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
