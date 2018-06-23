<?php

class StationpersonController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
        $id = Yii::app()->request->getParam('id',0);
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($id) {
            $sql = "select * from my_sysuser where id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有该站点人员！'
                );
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该站点人员！'
            );
        }
        echo json_encode($ret);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Sysuser;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        if(isset($_POST))
        {
            if (empty($_POST['password'])){
                $msg = "密码为空";
            }
            if (empty($_POST['unit'])){
                $msg = "单位为空";
            }
            if (empty($_POST['backup_phone'])){
                $msg = "备用电话为空";
            }
            if (empty($_POST['name'])){
                $msg= "姓名为空";
            }
            if (empty($_POST['phone'])){
                $msg = "电话为空";
            }
            if (empty($_POST['postname'])){
                $msg = "职位为空";
            }
            if (empty($_POST['email'])){
                $msg = "邮箱为空";
            }
            if (empty($_POST['location'])){
                $msg = "地址为空";
            }
            if (empty($_POST['duty_num'])){
                $msg = "班次为空";
            }
            // print_r($_POST);
            $username = $_POST['username'];
            if (empty($username)){
                $msg = "登陆名为空";
            }
            if (empty($_POST['area'])){
                $msg = "区域为空";
            }

            if (!empty($msg)){
                $ret['response'] = array(
                    'code' => -2,
                    'msg' => $msg
                );
                echo json_encode($ret);exit;
            }
            // print_r($_POST);exit();
            $sql = "select * from my_sysuser where username = '$username'";
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row){
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => 'has the same username'
                );
                echo json_encode($ret);exit;
            }
            $model->attributes=$_POST;
            $model->refresh = $_POST['refresh'];
            $model->salt = Utils::createCode();
            $model->password = md5($_POST['password'].$model->salt);
            if($model->save()){
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$_SESSION['username'],
                    'content'=>$_SESSION['username']."添名为 $username 的用户信息",
                    'oldvalue'=>'',
                    'newvalue'=>json_encode($model->attributes)
                );
                $this->addlog($log);
                $ret['data'] = array(
                    'id'=>$model->id
                );
            }
        }
        echo json_encode($ret);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{

        $id = Yii::app()->request->getParam('id' ,0);
        $model=Sysuser::model()->findByAttributes(array('id' => $id));
        //$model=$this->loadModel($id);

        // var_dump($model);
        // return;

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        if ($model) {
            // $sid=Yii::app()->request->getParam('sid','');
            $oldvalue = $model->attributes;
            $model->attributes=$_POST;
            $model->refresh = $_POST['refresh'];
            // echo $oldvalue['password'],$_POST['password'];
            if ($oldvalue['password'] != $_POST['password']){
                $model->password = md5($_POST['password'].$model->salt);
                // echo $model->password;exit;
            }
            // var_dump($model->attributes);exit;
                if ($model->save()) {
                    $log = array(
                        'type'=>2,
                        'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                        'username'=>$_SESSION['username'],
                        'content'=>$_SESSION['username']."将 $_POST[username] 的用户信息更新了",
                        'oldvalue'=>json_encode($oldvalue),
                        'newvalue'=>json_encode($model->attributes)
                    );
                    $this->addlog($log);
                    $ret['data'] = array(
                        'id'=>$model->id,
                        'Operater'=>$model->username,
                    );
                }else{
                    $ret['response'] = array(
                        'code'=>-1,
                        'msg'=>'更新站点人员失败！'
                    );
                }

        }
        echo json_encode($ret);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
        $id = Yii::app()->request->getParam('id' ,0);
        $model=Sysuser::model()->findByAttributes(array('id' => $id));
        $ret['response'] = array(
            'code' => 0,
            'msg' => '删除设备成功!'
        );
        $ret['data'] = array();
        $log = array(
            'type'=>2,
            'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
            'username'=>$_SESSION['username'],
            'content'=>$_SESSION['username']."将 $model->username 的用户信息删除了",
            'oldvalue'=>json_encode($model->attributes),
            'newvalue'=>''
        );
        $this->addlog($log);
        $result = $this->loadModel($id)->delete();

        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除设备失败！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        
        $this->setPageCount();
        $ups = Yii::app()->db->createCommand()
            ->select('sp.*,s.site_name,r.rolename')
            ->from('{{sysuser}} sp')
            ->leftJoin('{{site}} s','sp.sn_key = s.serial_number')
            ->leftJoin('{{roles}} r', 'sp.role = r.id')
            // ->limit($this->count)
            // ->offset(($this->page-1)*$this->count)
            ->order('id desc')
            ->queryAll();

        //xl
        $user_info = GeneralLogic::getUserInfo($_SESSION['uid']);
        if(!empty($user_info)){
            foreach($ups as $k => $up){
                //小于权限不显示
                if($up['role'] < $user_info['role']){
                    unset($ups[$k]);
                }else{
                    //不在地域内不显示
                    $flag = false;
                    $areas = explode(",",$up['area']);
                    foreach($areas as $area){
                        if($user_info['area'] == "*" || $area == "*" || in_array($area, $user_info['areas'])){
                            $flag = true;
                        }
                    }

                    if($flag == false){
                        unset($ups[$k]);
                    }
                }
            }
            $ups = empty($ups) ? array() : array_values($ups);
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($ups) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($ups as $key=>$value){
                // var_dump($value['area']);
                // $area = Yii::app()->db->createCommand("select title from my_trees where id=".$value['area'])->queryScalar();
                //$value['area'] = $area;
                $value['areaname'] = "123";

                if($value['area'] == "*"){
                    $value['areaname'] = "全国";
                }else{
                    $areas = explode(",",$value['area']);

                    $sql = "select title from my_trees where id in (".$value['area'].")";

                    $areanamelist = Yii::app()->db->createCommand($sql)->queryAll();

                    $areas = array();
                    foreach ($areanamelist as $akey => $avalue) {
                        $areas[] = $avalue['title'];
                    }
                    //var_dump($areas);
                    $value['areaname'] = implode(" ", $areas);
                }

                $ret['data']['list'][] = $value;
            }

        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无站点人员数据！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new StationPerson('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StationPerson']))
			$model->attributes=$_GET['StationPerson'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return StationPerson the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Sysuser::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param StationPerson $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='station-person-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
