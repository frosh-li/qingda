<?php
class GroupAlarmCommand extends CConsoleCommand
{
    //获取组的报警的command
    public function init()
    {
        // init
    }
    public function addAlarm($group,$value)
    {
        //添加一条报警信息
        $alarm = array();
        $alarm['alarm_sn'] = $group['sn_key'];
        $alarm['alram_equipment'] = '组报警';
        $alarm['equipment_sn'] = $group['sn_key'];
        $alarm['alarm_code'] = $value['alarm_code'];
        $alarm['alarm_content'] = $value['content'];
        $alarm['alarm_emergency_level'] = $value['alarm_type'];
        $alarm['alarm_suggestion'] = $value['suggest'];
        $alarm['alarm_suggestion'] = $value['suggest'];
        $alarm['alarm_occur_time'] = $value['record_time'];
        $alarm['alarm_recovered_time'] = $group['record_time'];
        $alarm['alarm_para1_name'] = substr($group['sn_key'],0,-2).'00';
        $sql = "select * from {{site}} where serial_number=".$alarm['alarm_para1_name'];
        $siteinfo = Yii::app()->bms->createCommand($sql)->queryRow();
        $alarm['alarm_para1_value'] = $siteinfo['site_name'];
        $alarm['alarm_para2_name'] = $group['sn_key'];
        $alarm['alarm_para2_value'] = '组'.$group['gid'];

        $sql = "select * from {{general_alarm}} where alarm_sn=".$group['sn_key']." and alarm_cancelled_by_man is null";
        $has = Yii::app()->bms->createCommand($sql)->queryRow();
        if ($has) {
            $upsql = Utils::buildUpdateSQL($alarm);
            $sql = "update {{general_alarm}} set ".$upsql." where alarm_sn=".$group['sn_key']." and alarm_cancelled_by_man is null";
        }else{
            $insql = Utils::buildInsertSQL($alarm);
            $sql = "insert into {{general_alarm}} ".$insql;
        }
        $exec = Yii::app()->bms->createCommand($sql)->execute();
        return $exec;
    }
    public function findSiteconf($group,$type)
    {
        $sql = "select * from {{alarm_siteconf}}
                                where category=3 and status=1 and
                                alarm_code= '".$type."' and sid=".$group['sid']." order by alarm_type asc";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        return $rows;
    }
    public function run($args)
    {
        $groups = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{group_module}}')
            ->where('Status1!=0 or Status2!=0')
            ->order('record_time desc')
            ->queryAll();
        if ($groups) {
            foreach ($groups as $index => $group) {
                $sql = "select * from {{group_parameter}} where group_sn_key=".$group['sn_key'];
                $row = Yii::app()->bms->createCommand($sql)->queryRow();
                if (!$row) {
                    // 如果没有参数就说明一下
                }
                if ($group['Status1'] || $group['Status2']) {
                    if ($group['C0bIDisChargeH']) {
                        // 组放电电流超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=2 and ac.status=1 and
                        // ac.alarm_code='lih' and ac.sid = ".$group['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($group,'lih');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($group['IoutMax'] /$row['DisbytegeCurrentLimit'])*100 >= $value['type_value'];
                                }else{
                                    $cha = ($group['IoutMax'] - $row['DisbytegeCurrentLimit']) >= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($group,$value);
                                    if ($ret) {
                                        echo 'group lih add alarm ok'."\n";
                                    }else{
                                        echo 'group lih add alarm error'."\n";
                                    }
                                }
                            }
                        }

                    }
                    if ($group['C1bIInChargeH']) {
                        // 组充电电流超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=2 and ac.status=1 and
                        // ac.alarm_code='lil' and ac.sid = ".$group['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($group,'lil');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($group['IinMax'] - $row['bytegeCurrentLimit'])*100 >= $value['type_value'];
                                }else{
                                    $cha = ($group['IinMax'] - $row['bytegeCurrentLimit']) >= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($group,$value);
                                    if ($ret) {
                                        echo 'group lil add alarm ok'."\n";
                                    }else{
                                        echo 'group lil add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($group['C2bTH']) {
                        // 组温度超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=2 and ac.status=1 and
                        // ac.alarm_code='lth' and ac.sid = ".$group['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($group,'lth');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($group['Tmax'] - $row['TemperatureHighLimit'])*100 >= $value['type_value'];
                                }else{
                                    $cha = ($group['Tmax'] - $row['TemperatureHighLimit']) >= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($group,$value);
                                    if ($ret) {
                                        echo 'group lth add alarm ok'."\n";
                                    }else{
                                        echo 'group lth add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($group['C3bTL']) {
                        // 组温度超下限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=2 and ac.status=1 and
                        // ac.alarm_code='ltl' and ac.sid = ".$group['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($group,'ltl');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($group['Tmin'] - $row['TemperatureLowLimit'])*100 <= $value['type_value'];
                                }else{
                                    $cha = ($group['Tmin'] - $row['TemperatureLowLimit']) <= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($group,$value);
                                    if ($ret) {
                                        echo 'group ltl add alarm ok'."\n";
                                    }else{
                                        echo 'group ltl add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($group['C4bHumiH']) {
                        // 组湿度超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=2 and ac.status=1 and
                        // ac.alarm_code='lhl' and ac.sid = ".$group['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($group,'lhl');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($group['HumiHigh'] / $row['HumiH'])*100 >= $value['type_value'];
                                }else{
                                    $cha = ($group['HumiHigh'] - $row['HumiH']) >= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($group,$value);
                                    if ($ret) {
                                        echo 'group lhl add alarm ok'."\n";
                                    }else{
                                        echo 'group lhl add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($group['C5bHumiLow']) {
                        // 组湿度超下限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=2 and ac.status=1 and
                        // ac.alarm_code='lhl' and ac.sid = ".$group['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($group,'lhl');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($group['HumiLow'] - $row['HumiL'])*100 >= $value['type_value'];
                                }else{
                                    $cha = ($group['HumiLow'] - $row['HumiL']) >= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($group,$value);
                                    if ($ret) {
                                        echo 'group lhl add alarm ok'."\n";
                                    }else{
                                        echo 'group lhl add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($group['C6NoCurrentSensor']) {
                        // 未安装组电流传感器
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($group['C7bCurrentSensorError']) {
                        // 组电流传感器故障
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($group['C8bTErr']) {
                        // 温湿度测量错误
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($group['C9bCommErr']) {
                        // 电流模块通讯故障
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                }
            }
        }
    }


}