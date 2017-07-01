<?php
class StationAlarmCommand extends CConsoleCommand
{
    //获取站报警的command
    public function init()
    {
        // init
    }
    public function alarm($msg)
    {
        echo $msg."\n";
    }
    public function addAlarm($site,$value)
    {
        //添加一条报警信息
        $alarm = array();
        $alarm['alarm_sn'] = $site['sn_key'];
        $alarm['alram_equipment'] = '站报警';
        $alarm['equipment_sn'] = $site['sn_key'];
        $alarm['alarm_code'] = $value['alarm_code'];
        $alarm['alarm_content'] = $value['content'];
        $alarm['alarm_emergency_level'] = $value['alarm_type'];
        $alarm['alarm_suggestion'] = $value['suggest'];
        $alarm['alarm_suggestion'] = $value['suggest'];
        $alarm['alarm_occur_time'] = $value['record_time'];
        $alarm['alarm_recovered_time'] = $site['record_time'];
        $alarm['alarm_para1_name'] = $site['sn_key'];
        $sql = "select * from {{site}} where serial_number=".$site['sn_key'];
        $siteinfo = Yii::app()->bms->createCommand($sql)->queryRow();
        $alarm['alarm_para1_value'] = $siteinfo['site_name'];

        $sql = "select * from {{general_alarm}} where alarm_sn=".$site['sn_key']." and alarm_cancelled_by_man is null";
        $has = Yii::app()->bms->createCommand($sql)->queryRow();
        if ($has) {
            $upsql = Utils::buildUpdateSQL($alarm);
            $sql = "update {{general_alarm}} set ".$upsql." where alarm_sn=".$site['sn_key']." and alarm_cancelled_by_man is null";
        }else{
            $insql = Utils::buildUpdateSQL($alarm);
            $sql = "insert into {{general_alarm}} ".$insql;
        }
        $exec = Yii::app()->bms->createCommand($sql)->execute();
        return $exec;
    }
    public function findSiteconf($site,$type)
    {
        $sql = "select * from {{alarm_siteconf}}
                                where category=3 and status=1 and
                                alarm_code= '".$type."' and sid=".$site['sid']." order by alarm_type asc";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        return $rows;
    }
    public function run($args)
    {
        $sites = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{station_module}}')
            ->order('record_time desc')
            ->where('Status1!=0 or Status2!=0')
            ->queryAll();
        if ($sites) {
            foreach ($sites as $index => $site) {
                $sql = "select * from {{station_parameter}} where station_sn_key=".$site['sn_key'];
                $row = Yii::app()->bms->createCommand($sql)->queryRow();
                if (!$row) {
                        // 如果没有参数就说明一下
                    $this->alarm('没有站参数');
                }    
                if ($site['Status1'] || $site['Status2']) {
                    if ($site['B0_TH']) {
                        // 站环境温度超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        //left join {{site}}  as s on ac.sid = s.sid
                        // where ac.category=1 and ac.status=1 and
                        // ac.alarm_code='lch' and s.serial_number = ".$site['sn_key']." order by  alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($site,'lch');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($site['T'] - $row['T_upper_limit'])*100 >= $value['type_value'];
                                }else{
                                    $cha = ($site['T'] - $row['T_upper_limit']) >= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($site,$value);
                                    if ($ret) {
                                        echo 'site lch add alarm ok'."\n";
                                    }else{
                                        echo 'site lch alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($site['B1_TL']) {
                        // 站环境温度超下限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        //left join {{site}}  as s on ac.sid = s.sid
                        // where ac.category=1 and ac.status=1 and
                        // ac.alarm_code='lcl' and s.serial_number = ".$site['sn_key']." order by  alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($site,'lcl');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($site['T'] - $row['T_lower_limit'])*100 <= $value['type_value'];
                                }else{
                                    $cha = ($site['T'] - $row['T_lower_limit']) <= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($site,$value);
                                    if ($ret) {
                                        echo 'site lcl add alarm ok'."\n";
                                    }else{
                                        echo 'site lcl alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($site['B2_HumiH']) {
                        // 站环境温度超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        //left join {{site}}  as s on ac.sid = s.sid
                        // where ac.category=1 and ac.status=1 and
                        // ac.alarm_code='luh' and s.serial_number = ".$site['sn_key']." order by  alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($site,'luh');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($site['Humi'] - $row['Humi_upper_limit'])*100 <= $value['type_value'];
                                }else{
                                    $cha = ($site['Humi'] - $row['Humi_upper_limit']) <= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($site,$value);
                                    if ($ret) {
                                        echo 'site luh add alarm ok'."\n";
                                    }else{
                                        echo 'site luh alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($site['B3_HumiL']) {
                        // 站环境湿度超下限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        //left join {{site}}  as s on ac.sid = s.sid
                        // where ac.category=1 and ac.status=1 and
                        // ac.alarm_code='lul' and s.serial_number = ".$site['sn_key']." order by  alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($site,'lul');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($site['Humi'] - $row['Humi_lower_limit'])*100 <= $value['type_value'];
                                }else{
                                    $cha = ($site['Humi'] - $row['Humi_lower_limit']) <= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($site,$value);
                                    if ($ret) {
                                        echo 'site lul add alarm ok'."\n";
                                    }else{
                                        echo 'site lul alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($site['B4_TtestFailed']) {
                        // 站环境温度测量失败
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        //left join {{site}}  as s on ac.sid = s.sid
                        // where ac.category=1 and ac.status=1 and
                        // ac.alarm_code='lul' and s.serial_number = ".$site['sn_key']." order by  alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        //if ($rows) {
                        //    foreach ($rows as $key => $value) {
                        //        if ($value['judge_type'] == 1) {
                        //            $cha = ($site['Humi'] - $row['Humi_lower_limit'])*100 <= $value['type_value'];
                        //        }else{
                        //            $cha = ($site['Humi'] - $row['Humi_lower_limit']) <= $value['type_value'];
                        //        }
                        //
                        //        if ($cha) {
                        //            // 记录一条站报警
                        //            $this->addAlarm($site,$value);
                        //            break;
                        //        }
                        //    }
                        //}
                    }
                    if ($site['B5_noIsensor']) {
                        // 站未安装电流传感器
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($site['B6_LightSound_dev_error']) {
                        // 站声光报警设备的异常
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($site['B7_AirConditioner_HumiDev_Error']) {
                        // 站空调及加湿器控制异常
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($site['B8_NoConnection']) {
                        // 站无连接
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($site['B9_NoData_but_Connected']) {
                        // 站有链接无数据
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($site['BA_Data_Error']) {
                        // 站数据格式错误
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($site['BB_Offline_Often']) {
                        // 站掉线频繁
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                }
            }
        }
    }

}