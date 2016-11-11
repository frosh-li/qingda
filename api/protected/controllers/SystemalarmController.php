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
        $this->setPageCount();
        $offset = ($this->page-1)*$this->count;

        $sql = "select * from systemAlarm  left join my_site on (my_site.serial_number=systemAlarm.station) order by systemAlarm.id desc";
        $ups = Yii::app()->db->createCommand()
                ->select("*")
                ->from("systemAlarm")
                ->leftJoin("my_site","my_site.serial_number=systemAlarm.station")
                ->order("systemAlarm.id desc")
                ->limit($this->count)
                ->offset(($this->page - 1) * $this->count)
                ->queryAll();

        $upsTotal = Yii::app()->createCommand()
                ->select("count(*) as totals")
                ->from("systemAlarm")
                ->order("systemAlarm.id desc")
                ->queryScalar();
        
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
