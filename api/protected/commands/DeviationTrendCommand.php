<?php
class DeviationTrendCommand extends CConsoleCommand
{
//    const TIME = 3600;
    const TIME = 36000000;
    //获取电池的报警的command
    public function init()
    {
        // init
    }

    public function run($args)
    {
        $errorBatteryData = $this->getErrorBatteryData();
        foreach ($errorBatteryData as $data) {
            if (!$this->isIn($data)) {
                $this->insertErrorData($data);
            }
        }

        echo "DeviationTrend completed!\n";
    }

    private function getErrorBatteryData() {
        $time = date('Y-m-d H:i:s', time() - self::TIME);
        $where = "record_time > '{$time}' and (`Status1` != 0 or `Status2` != 0)";
        $result = Yii::app()->bms->createCommand()
            ->select('sn_key,record_time,sid,gid,U,T,R,B0bUH,B1bUL,B2bTH,B3bTL,B4bRH')
            ->from('{{battery_module_history}}')
            ->where($where)->queryAll();

        return $result;
    }

    private function insertErrorData(array  $data) {
        $where = "record_time = '{$data['record_time']}' and sid={$data['sid']} and gid={$data['gid']}";
        $result = Yii::app()->bms->createCommand()
            ->select('sn_key,record_time,sid,gid,U,T,R,B0bUH,B1bUL,B2bTH,B3bTL,B4bRH')
            ->from('{{battery_module_history}}')
            ->where($where)->queryAll();

        $u = 0;
        $t = 0;
        $r = 0;
        foreach ($result as $v) {
            $u += floatval($v['U']);
            $t += floatval($v['T']);
            $r += floatval($v['R']);
        }

        $count = count($result);
        if ($count > 0) {
            $u = $u / $count;
            $t = $t / $count;
            $r = $r / $count;
        }

        $dataArray = array(
                        'sid' => $data['sid'],
                        'gid' => $data['gid'],
                        'sn_key' => $data['sn_key'],
                        'EU' => $data['U'],
                        'ET' => $data['T'],
                        'ER' => $data['R'],
                        'U' => $u,
                        'T' => $t,
                        'R' => $r,
                        'record_time' => $data['record_time'],
        );
        Yii::app()->db->createCommand()->insert('{{deviation_trend}}', $dataArray);
    }

    private function isIn(array $data) {
        $where = "sid='{$data['sid']}' and gid='{$data['gid']}' and record_time='{$data['record_time']}'";
        $result = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('{{deviation_trend}}')
                    ->where($where)
                    ->limit(1)->queryAll();
        return (sizeof($result) > 0);
    }
}
