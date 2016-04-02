<?php
class BatteryAlarmCommand extends CConsoleCommand
{
    //获取电池的报警的command
    public function init()
    {
        // init
        echo 'init';
    }

    public function alarm($msg)
    {
        echo $msg."\n";
    }
    public function addAlarm($bettary,$value,$type)
    {
        //添加一条报警信息
        $alarm = array();
        $alarm['alarm_sn'] = $bettary['sn_key'];
        $alarm['alram_equipment'] = '组报警';
        $alarm['equipment_sn'] = $bettary['sn_key'];
        $alarm['alarm_code'] = $value['alarm_code'];
        $alarm['alarm_content'] = $value['content'];
        $alarm['alarm_emergency_level'] = $value['alarm_type'];
        $alarm['alarm_suggestion'] = $value['suggest'];
        $alarm['alarm_occur_time'] = $value['record_time'];
        $alarm['alarm_recovered_time'] = $bettary['record_time'];
        $alarm['alarm_para1_name'] = substr($bettary['sn_key'],0,-4).'0000';
        $sql = "select * from {{site}} where serial_number=".$alarm['alarm_para1_name'];
        $siteinfo = Yii::app()->db->createCommand($sql)->queryRow();
        $alarm['alarm_para1_value'] = $siteinfo['site_name'];
        $alarm['alarm_para2_name'] = substr($bettary['sn_key'],0,-2).'00';
        $alarm['alarm_para2_value'] = '组'.$bettary['gid'];
        $alarm['alarm_para3_name'] = $bettary['sn_key'];
        $alarm['alarm_para3_value'] = '电池'.$bettary['bid'];


        $sql = "select * from {{general_alarm}} where alarm_sn=".$bettary['sn_key']." and alarm_code='".$value['alarm_code']."' and alarm_cancelled_by_man is null";
        echo $type.$sql."\n";
        $has = Yii::app()->bms->createCommand($sql)->queryRow();
        if ($has) {
            $upsql = Utils::buildUpdateSQL($alarm);
            $sql = "update {{general_alarm}} set ".$upsql." where alarm_sn=".$bettary['sn_key']." and alarm_code='".$value['alarm_code']."' and alarm_cancelled_by_man is null";
        }else{
            $insql = Utils::buildInsertSQL($alarm);
            $sql = "insert into {{general_alarm}} ".$insql;
        }
        $exec = Yii::app()->bms->createCommand($sql)->execute();
        return $exec;
    }

    public function findSiteconf($battery,$type)
    {
        $sql = "select * from {{alarm_siteconf}}
                                where category=3 and status=1 and
                                alarm_code= '".$type."' and sid=".$battery['sid']." order by alarm_type asc";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        return $rows;
    }
    public function run($args)
    {
        $batteries = Yii::app()->bms->createCommand()
            ->select('*')
            ->from('{{battery_module}}')
            ->where('Status1!=0 or Status2!=0')
            ->order('record_time desc')
            ->queryAll();
        if ($batteries) {
            foreach ($batteries as $index => $battery) {
                $sql = "select * from {{battery_parameter}} where battery_sn_key=".$battery['sn_key'];
                $row = Yii::app()->bms->createCommand($sql)->queryRow();
                if (!$row) {
                    // 如果没有参数就说明一下
                    $this->alarm('没有电池参数');
                }
                if ($battery['Status1'] || $battery['Status2']) {
                    if ($battery['B0bUH']) {
                        // 电压超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=3 and ac.status=1 and
                        // ac.alarm_code='b0b' and ac.sid = ".$battery['sid']." order by  ac.alarm_type asc";
                        //$sql = "select * from {{alarm_siteconf}}
                        //        where category=3 and status=1 and
                        //        alarm_code= 'b0b' and sid=".$battery['sid']." order by alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($battery,'b0b');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($battery['U'] /$row['BatteryU_H'])*100 >= $value['type_value'] ;
                                }else{
                                    $cha = ($battery['U'] - $row['BatteryU_H']) >= $value['type_value'];
                                }

                                if ($cha) {
                                    //echo $battery['U'].'-'.$row['BatteryU_H'].'-'.$value['type_value'];
                                    //echo $cha;
                                    //exit;
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($battery,$value,'b0b');
                                    if ($ret) {
                                        echo 'b0b add alarm ok'."\n";
                                    }else{
                                        echo 'b0b add alarm error'."\n";
                                    }
                                }
                            }
                        }

                    }
                    if ($battery['B1bUL']) {
                        // 电压超下限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=3 and ac.status=1 and
                        // ac.alarm_code='b1b' and ac.sid = ".$battery['sid']." order by  ac.alarm_type asc";
                        //$sql = "select * from {{alarm_siteconf}}
                        //        where category=3 and status=1 and
                        //        alarm_code= 'b1b' and sid=".$battery['sid']." order by alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($battery,'b1b');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($battery['U'] - $row['BatteryU_H'])*100 <= $value['type_value'];
                                }else{
                                    $cha = ($battery['U'] - $row['BatteryU_H']) <= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($battery,$value,'b1b');
                                    if ($ret) {
                                        echo 'b1b add alarm ok'."\n";
                                    }else{
                                        echo 'b1b add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($battery['B2bTH']) {
                        // 温度超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=3 and ac.status=1 and
                        // ac.alarm_code='b2b' and ac.sid = ".$battery['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($battery,'b2b');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($battery['T'] / $row['Electrode_T_H_Limit'])*100 >= $value['type_value'];
                                }else{
                                    $cha = ($battery['T'] - $row['Electrode_T_H_Limit']) >= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($battery,$value,'b2b');
                                    if ($ret) {
                                        echo 'b2b add alarm ok'."\n";
                                    }else{
                                        echo 'b2b add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($battery['B3bTL']) {
                        // 温度超下限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=3 and ac.status=1 and
                        // ac.alarm_code='b3b' and ac.sid = ".$battery['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($battery,'b3b');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($battery['T'] - $row['Electrode_T_L_Limit'])*100 <= $value['type_value'];
                                }else{
                                    $cha = ($battery['T'] - $row['Electrode_T_L_Limit']) <= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($battery,$value,'b3b');
                                    if ($ret) {
                                        echo 'b3b add alarm ok'."\n";
                                    }else{
                                        echo 'b3b add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($battery['B4bRH']) {
                        // 内阻超上限
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                        //$sql = "select ac.* from {{alarm_conf}} as ac
                        // where ac.category=3 and ac.status=1 and
                        // ac.alarm_code='b4b' and ac.sid = ".$battery['sid']." order by  ac.alarm_type asc";
                        //$rows = Yii::app()->db->createCommand($sql)->queryAll();
                        $rows = $this->findSiteconf($battery,'b4b');
                        if ($rows) {
                            foreach ($rows as $key => $value) {
                                if ($value['judge_type'] == 1) {
                                    $cha = ($battery['R'] - $row['Rin_High_Limit'])*100 <= $value['type_value'];
                                }else{
                                    $cha = ($battery['R'] - $row['Rin_High_Limit']) <= $value['type_value'];
                                }

                                if ($cha) {
                                    // 记录一条站报警
                                    $ret = $this->addAlarm($battery,$value,'b4b');
                                    if ($ret) {
                                        echo 'b4b add alarm ok'."\n";
                                    }else{
                                        echo 'b4b add alarm error'."\n";
                                    }
                                }
                            }
                        }
                    }
                    if ($battery['B5bR_period_measure_errror']) {
                        // 电池周期性测内阻失败
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['B6bReserv']) {
                        // 备用
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['B7bCommErr']) {
                        // 通讯异常
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['B8bUErr']) {
                        // 电压异常
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['B9bTErr']) {
                        // 温度异常
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['BAbRErr']) {
                        // 内阻异常
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['BBbCharge']) {
                        // 处于充电态
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['BCbDisCharge']) {
                        // 处于放电态
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['BDbNewR_flag']) {
                        // 新测的内阻
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['BEbR_measre_failed']) {
                        // 内阻测量失败
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                    if ($battery['BFbReserve']) {
                        // 备用
                        //读出该站的参数设置
                        //比较之后 ，判断颜色，放入数据库
                    }
                }
            }
        }
    }
}