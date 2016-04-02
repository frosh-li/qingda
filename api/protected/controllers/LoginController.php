<?php

class LoginController extends LController
{
	public function actionIndex()
	{
		$username = Yii::app()->request->getParam('username','');
        $password = Yii::app()->request->getParam('password','');
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        if ($username == '' || $password =='') {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'用户名或密码不能为空！'
            );
        }else{
            $sql = "select * from {{sysuser}} where username='".$username."'";
            $row = Yii::app()->db->createCommand($sql)->queryRow();

            if ($row) {
                if (md5($password.$row['salt']) == $row['password']) {
                    $ret['data'] = array(
                        'uid'=>$row['id'],
                        'role'=>$row['role']
                    );
                    $_SESSION['uid'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    $log = array(
                        'type'=>1,
                        'uid'=>$row['id'],
                        'username'=>$row['username'],
                        'content'=>$row['username']."登录了系统",
                        'oldvalue'=>'',
                        'newvalue'=>''
                    );
                    $this->addlog($log);
                }else{
                    $ret['response'] = array(
                        'code'=>-1,
                        'msg'=>'用户名或密码错误！'
                    );
                }
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该用户！'
                );
            }
        }
        echo json_encode($ret);
        Yii::app()->end();
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