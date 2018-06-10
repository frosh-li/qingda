<?php

class IRCollectController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';
    public function actionClear() {
        
        Yii::app()->db->createCommand("delete FROM  {{collect}}")->execute();

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        echo json_encode($ret);
    }
	public function actionUpdate(){
        // 内阻采集之前清理表里面的数据
        Yii::app()->db->createCommand("delete FROM  {{collect}}")->execute();

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $contact = isset($_SESSION['username']) ? $_SESSION['username'] : '未知'; 
        $sn_key = Yii::app()->request->getParam('stationid', '');
        $log = array(
            'type'=>3,
            'uid'=>$this->session->getUid(),
            'username'=>$contact,
            'content'=>$contact."内阻采集，站点$sn_key",
            'oldvalue'=>'',
            'newvalue'=>''
        );
        $this->addlog($log); 
        
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
        $query = '';
        if (count($sn_key_list) > 0){
            $query = 'WHERE b.stationid in ('.implode(',', $sn_key_list).')';
        }
        
        unset($where,$sql);

        $this->setPageCount();
        $offset = ($this->page-1)*$this->count;

         
        

        $sql = "SELECT b . * , s.site_name, s.sid
            FROM  {{collect}} AS b
            LEFT JOIN {{site}} AS s ON b.stationid = s.serial_number $query
            order by collect_time desc ";

        $total = Yii::app()->db->createCommand("SELECT count(*) as totals 
            FROM  {{collect}} AS b
            LEFT JOIN {{site}} AS s ON b.stationid = s.serial_number $query")->queryScalar();
         
        $ups = Yii::app()->db->createCommand($sql)->queryAll();
        
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $ret['data']['page'] = $this->page;
            
        if ($ups) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;
            $ret['data']['totals'] = intval($total);
            foreach($ups as $key=>$value){
                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '采集数据！'
            );
        }

        echo json_encode($ret);

	}
}
