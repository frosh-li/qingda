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

    function chanagepwd(){
        $username = Yii::app()->request->getParam('username','');
        $password = Yii::app()->request->getParam('password','');
        if (empty($username) || empty($password)){
            $ret['response'] = array(
                'code'=>-1,
                'msg'=> 'username 或 password 为空'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }
        // print_r($this->session->getUserName());
        if (!$this->session->isLogin()){
            $ret['response'] = array(
                'code'=>-1,
                'msg'=> '未登录'
            );
            echo json_encode($ret);
            Yii::app()->end();
        }
        $uname = $this->session->getUserName();
        if ($username != $uname){
            $ret['response'] = array(
                'code'=>-1,
                'msg'=> '输入的账号不为当前账号'
            );
            $log = array(
                'type'=>3,
                'uid'=>$this->session->getUid(),
                'username'=>$this->session->getUserName(),
                'content'=>$this->session->getUserName()."尝试修改他人密码失败",
                'oldvalue'=>'',
                'newvalue'=>''
            );
            $this->addlog($log);
            echo json_encode($ret);
            Yii::app()->end();
        }
        // $sql = "select * from {{sysuser}} where username='".$uname."'";
        // $row = Yii::app()->db->createCommand($sql)->queryRow();
        $sql = "update {{sysuser}} set password = '$password' where username = '$username'";
        Yii::app()->db->createCommand($sql)->execute();
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );

        $log = array(
            'type'=>3,
            'uid'=>$this->session->getUid(),
            'username'=>$this->session->getUserName(),
            'content'=>$this->session->getUserName()."成功修改密码",
            'oldvalue'=>'',
            'newvalue'=>''
        );
        $this->addlog($log);
        $ret['data'] = array();

        echo json_encode($ret);
        Yii::app()->end();
    }

    public function actionIndex()
	{
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );

        // if ($this->session->isLogin()) {
        //     $ret['data'] = array(
        //         'uid'=>$this->session->getUid(),
        //         'role'=>$this->session->getRole(),
        //         'area'=>$this->session->getArea()
        //     );
        //     echo json_encode($ret);
        //     Yii::app()->end();
        // }
        if (isset($_POST['chanagepwd']) && $_POST['chanagepwd'] == 1){
            $this->chanagepwd();
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
            $sql = "select * from {{sysuser}} where username=:username";
            $row = Yii::app()->db->createCommand($sql)->bindValues([':username' => $username])->queryRow();

            // var_dump($row);
            if ($row) {
                if ($password == $row['password']) {
                    $ret['data'] = array(
                        'username'=>$username,
                        'uid'=>$row['id'],
                        'role'=>$row['role'],
                        'area'=>$row['area'],
                        'canedit' => $row['canedit']
                    );
                    $this->session->register($row['id'], $row['username'], $row['role'],$row['area'],$row['canedit']);
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
                        'msg'=>'密码错误，请输入正确的用户名和密码！'
                    );
                }
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'无此用户，请输入正确的用户名和密码！'
                );
            }
        }
        echo json_encode($ret);
        Yii::app()->end();
	}

    public function actionLoginOut() {
        $uid = $_SESSION['uid'];
        $username = $_SESSION['username'];
        $this->session->loginOut();
        $ret = array();
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        ); 
        $log = array(
            'type'=>1,
            'uid'=>$uid,
            'username'=>$username,
            'content'=>$username."登出了系统",
            'oldvalue'=>'',
            'newvalue'=>''
        );
        $this->addlog($log);
        echo json_encode($ret);
        Yii::app()->end();
    }
}
