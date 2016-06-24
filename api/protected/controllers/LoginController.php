<?php

class LoginController extends LController
{
    /**
     * @var Session
     */
    private $session;

    public function init()
    {
        parent::init();
        $this->session = new Session();
        $this->session->open();
    }

    public function actionIndex()
	{
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );

        if ($this->session->isLogin()) {
            $ret['data'] = array(
                'uid'=>$this->session->getUid(),
                'role'=>$this->session->getRole(),
                'area'=>$this->session->getArea()
            );
            echo json_encode($ret);
            Yii::app()->end();
        }

		$username = Yii::app()->request->getParam('username','');
        $password = Yii::app()->request->getParam('password','');
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
                if ($password == $row['password']) {
                    $ret['data'] = array(
                        'uid'=>$row['id'],
                        'role'=>$row['role'],
                        'area'=>$row['area']
                    );
                    $this->session->register($row['id'], $row['username'], $row['role'],$row['area']);
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

    public function actionLoginOut() {
        $this->session->loginOut();
        $ret = array();
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );

        echo json_encode($ret);
        Yii::app()->end();
    }
}