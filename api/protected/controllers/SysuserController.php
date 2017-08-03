<?php

class SysuserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

    public function init()
    {
        if (!YII_DEBUG) {
            if ($_SESSION['role'] != 1) {
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => '您没有权限操作！'
                );
                $ret['data'] = array();
                echo json_encode($ret);
                Yii::app()->end();
            }
        }
    }
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
            $sql = "select * from {{sysuser}} where id=" . $id;
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $ret['data'] = $row;
            }
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该用户！'
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
        $username = Yii::app()->request->getParam('username','');
        $password = Yii::app()->request->getParam('password','');
        $name = Yii::app()->request->getParam('name','');
        $gender = Yii::app()->request->getParam('gender','m');
        $role = Yii::app()->request->getParam('role','0');
        $phone = Yii::app()->request->getParam('phone','');
        $email = Yii::app()->request->getParam('email','');
        $postname = Yii::app()->request->getParam('postname','');
        $location = Yii::app()->request->getParam('location','');
        $site = Yii::app()->request->getParam('site','');
        $model=new Sysuser;

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        if ($username == '' || $password == '' || $role ==0) {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'添加用户信息不能为空！'
            );
        }else{
            $model->username = $username;
            $model->salt = Utils::createCode();
            $model->password = md5($password.$model->salt);
            $model->name = $name;
            $model->gender = $gender;
            $model->role = $role;
            $model->phone = $phone;
            $model->email = $email;
            $model->postname = $postname;
            $model->location = $location;
            $model->site = $site;
            if ($model->save()) {
                $ret['data'] = array(
                    'uid'=>$model->id,
                    'username'=>$model->username,
                    'role' =>$model->role
                );
            }else{
                var_dump($model->getErrors());
                exit;
            }

        }
        echo json_encode($ret);
        Yii::app()->end();

	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
        $id = Yii::app()->request->getParam('id',0);

        $model=$this->loadModel($id);

        $username = Yii::app()->request->getParam('username','');
        $password = Yii::app()->request->getParam('password','');
        $name = Yii::app()->request->getParam('name','');
        $gender = Yii::app()->request->getParam('gender','m');
        $role = Yii::app()->request->getParam('role','0');
        $phone = Yii::app()->request->getParam('phone','');
        $email = Yii::app()->request->getParam('email','');
        $postname = Yii::app()->request->getParam('postname','');
        $location = Yii::app()->request->getParam('location','');
        $site = Yii::app()->request->getParam('site','');

        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();
        if ($username == '' || $password == '' || $role ==0) {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'添加用户信息不能为空！'
            );
        }else{
            $model->username = $username;
            $model->salt = Utils::createCode();
            $model->password = md5($password.$model->salt);
            $model->name = $name;
            $model->gender = $gender;
            $model->role = $role;
            $model->phone = $phone;
            $model->email = $email;
            $model->postname = $postname;
            $model->location = $location;
            $model->site = $site;
            if ($model->save()) {
                $ret['data'] = array(
                    'uid'=>$model->id,
                    'username'=>$model->username,
                    'role' =>$model->role
                );
            }else{
                var_dump($model->getErrors());
                exit;
            }

        }
        echo json_encode($ret);
        Yii::app()->end();
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
        $id = Yii::app()->request->getParam('id',0);
		$result = $this->loadModel($id)->delete();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        if (!$result) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除失败！'
            );
        }else{
            $ret['data'] = array(
                'uid'=>$id
            );
        }
        echo json_encode($result);

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $this->setPageCount();
        $sysusers = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{sysuser}}')
            //->limit($this->count)
            //->offset(($this->page-1)*$this->count)
            ->order('id desc')
            ->queryAll();
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();

        if ($sysusers) {
            $ret['data']['page'] = $this->page;
            $ret['data']['pageSize'] = $this->count;

            foreach($sysusers as $key=>$value){
                $ret['data']['list'][] = $value;
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '暂无用户！'
            );
        }
        echo json_encode($ret);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Sysuser('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Sysuser']))
			$model->attributes=$_GET['Sysuser'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Sysuser the loaded model
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
	 * @param Sysuser $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sysuser-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
