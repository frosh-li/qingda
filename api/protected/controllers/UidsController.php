<?php

class UidsController extends Controller
{
    public $expire = 900;
	public function actionIndex()
	{
		$command = Yii::app()->request->getParam('command','');
        $para1 = Yii::app()->request->getParam('para1','');
        $para2 = Yii::app()->request->getParam('para2','');
        $para3 = Yii::app()->request->getParam('para3','');
        $ret = $this->addcommand($command,$para1,$para2,$para3);
        echo json_encode($ret);

	}
    //强采内阻
    public function actionForcerin()
    {
        $pass = Yii::app()->request->getParam('pass','');
        $res = $this->checkPassword($pass);
        if (!$res) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '密码错误！'
            );
            echo json_encode($ret);
            exit;
        }
        $para1 = Yii::app()->request->getParam('para1','');
        $para2 = Yii::app()->request->getParam('para2','');
        $para3 = Yii::app()->request->getParam('para3','');
        $ret = $this->addcommand('force_Rin_sample',$para1,$para2,$para3);
        echo json_encode($ret);
    }
    public function addcommand($command,$para1,$para2='',$para3='')
    {
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'添加命令成功'
        );
        $ret['data'] = array();
        if ($command == '') {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '命令不能为空！'
            );
        }else{
            if ($command == 'force_Rin_sample') {
                $sql = "select * from {{Commu_UI_Ask_DS}}
                        where UI_Ask_DS='force_Rin_sample' and UI_para1=" . $para1."
                        order by id desc ";
                $row = Yii::app()->bms->createCommand($sql)->queryRow();
                if ($row && (time()-$row['modify_time']) <= $this->expire) {
                    $ret['response'] = array(
                        'code' => -1,
                        'msg' => '两次间隔时间小于15分钟！'
                    );
                    return $ret;
                }else{
                    $uids = array(
                        'ExcuteEnable'=>1,
                        'UI_Ask_DS'=>$command,
                        'UI_para1'=>$para1,
                        'UI_para2'=>$para2,
                        'UI_para3'=>$para3,
                    );
                }
            }else{
                $uids = array(
                    'ExcuteEnable'=>1,
                    'UI_Ask_DS'=>$command,
                    'UI_para1'=>$para1,
                    'UI_para2'=>$para2,
                    'UI_para3'=>$para3,
                );
            }
            $insql = Utils::buildInsertSQL($uids);
            $sql = "insert into {{Commu_UI_Ask_DS}} ".$insql;
            $exe = Yii::app()->bms->createCommand($sql)->execute();
            if ($exe) {
                return $ret;
            }
        }
    }
    public function checkPassword($password)
    {
        $sql = "select * from {{program_gerneral_parameters}} where id=1";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row['password'] == $password) {
            return true;
        }
        return false;
    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}