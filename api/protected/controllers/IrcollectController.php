<?php

class IRCollectController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	public function actionUpdate(){
        // 内阻采集之前清理表里面的数据
        Yii::app()->db->createCommand("delete FROM  {{collect}}")->execute();

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
        
        $this->setPageCount();
        $offset = ($this->page-1)*$this->count;

         
        

        $sql = "SELECT b . * , s.site_name, s.sid
            FROM  {{collect}} AS b
            LEFT JOIN {{site}} AS s ON b.stationid = s.serial_number 
            order by collect_time desc ";

        $total = Yii::app()->db->createCommand("SELECT count(*) as totals 
            FROM  {{collect}} AS b
            LEFT JOIN {{site}} AS s ON b.stationid = s.serial_number")->queryScalar();
         
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