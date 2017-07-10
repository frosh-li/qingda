<?php

class IRCollectController extends Controller
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
        
        $this->setPageCount();
        $offset = ($this->page-1)*$this->count;

         //xl
         //通过sql直接选择地域进行过滤
         $sns = GeneralLogic::getWatchSeriNumByAid($_SESSION['uid']);
         if(!empty($sns)){
             $sql = "SELECT b . * , s.site_name, s.sid
                FROM  {{collect}} AS b
                LEFT JOIN {{site}} AS s ON b.stationid = s.serial_number
                where s.serial_number in (" . implode(",", $sns) .") 
                order by collect_time desc 
                limit ". $this->count .
                " offset ".($this->page - 1) * $this->count;

         }
         elseif($sns === false){

            $sql = "SELECT b . * , s.site_name, s.sid
                FROM  {{collect}} AS b
                LEFT JOIN {{site}} AS s ON b.stationid = s.serial_number 
                order by collect_time desc 
                limit ". $this->count .
                " offset ".($this->page - 1) * $this->count;
         }
        $ups = Yii::app()->db->createCommand($sql)->queryAll();
        
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($ups) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

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
