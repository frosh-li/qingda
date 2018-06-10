<?php

class SystemalarmController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	public function actionUpdate(){
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        
        echo json_encode($ret);   
    }
	/**
	 * Lists all models.
	 */
	public function actionIndex()
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
                $sn_key_list[] = $value['serial_number'];
            }
        }
        unset($where,$sql);

        $query = '';
        if (count($sn_key_list) > 0){
            $query = 'where systemalarm.station in ('.implode(',', $sn_key_list).')';
        }

        $this->setPageCount();
        // $offset = ($this->page-1)*$this->count;

        // $sql = "select * from systemalarm  left join my_site on (my_site.serial_number=systemalarm.station) order by systemalarm.id desc";
        // $ups = Yii::app()->db->createCommand()
        //         ->select("*")
        //         ->from("systemalarm")
        //         ->leftJoin("my_site","my_site.serial_number=systemalarm.station")
        //         ->order("systemalarm.id desc")
        //         ->limit($this->count)
        //         ->offset(($this->page - 1) * $this->count)
        //         ->queryAll();
        $end = $this->count;
        $start = ($this->page - 1) * $end;
        $sql = "select * from systemalarm left join my_site on my_site.serial_number = systemalarm.station $query order by systemalarm.id desc limit $start,$end";
        $ups = Yii::app()->db->createCommand($sql)->queryAll();

        // $upsTotal = Yii::app()->db->createCommand()
        //         ->select("count(*) as totals")
        //         ->from("systemalarm")
        //         ->order("systemalarm.id desc")
        //         ->queryScalar();
        $sql = "select count(*) as totals from systemalarm $query order by systemalarm.id desc";
        $upsTotal = Yii::app()->db->createCommand($sql)->queryScalar();
        
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($ups) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;
            $ret['data']['totals'] = intval($upsTotal);
            foreach($ups as $key=>$value){
                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '无系统报警数据！'
            );
        }

        echo json_encode($ret);

	}
}
