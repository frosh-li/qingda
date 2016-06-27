<?php

class TreesController extends Controller
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

    public function actionSetlevel()
    {
        $level = Yii::app()->request->getParam('level',0);
        if ($level == 0) {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'层级不能为空！'
            );
            $ret['data'] = array();
        }else{
            Yii::app( )->config->set( 'tree_level', $level );
            $ret['response'] = array(
                'code'=>0,
                'msg'=>'ok'
            );
            $ret['data'] = array();
        }
        echo json_encode($ret);
    }
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $parent = Yii::app()->request->getParam('pid',0);
        $title = Yii::app()->request->getParam('title','');
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        $model=new Trees;

        if ($parent == 0) {
            $model->title = $title;
            $model->pid = 0;
            $model->ishidden = 0;
            $model->content = $title;
            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'pid'=>0,
                    'title'=>$model->title
                );
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$username,
                    'content'=>$username."创建了一级树形结构",
                    'oldvalue'=>'',
                    'newvalue'=>''
                );
                $this->addlog($log);
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'保存结构出错！'
                );
            }
        }else{
            $sql = "select * from {{trees}} where id='".$parent."'";
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $model->title = $title;
                $model->pid = $row['id'];
                $model->ishidden = 0;
                $model->content = $title;
                if ($model->save()) {
                    $ret['data'] = array(
                        'id'=>$model->id,
                        'pid'=>$model->pid,
                        'title'=>$model->title
                    );
                    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                    $log = array(
                        'type'=>2,
                        'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                        'username'=>$username,
                        'content'=>$username."创建了二级树形结构",
                        'oldvalue'=>'',
                        'newvalue'=>''
                    );
                    $this->addlog($log);
                }else{
                    $ret['response'] = array(
                        'code'=>-1,
                        'msg'=>'保存结构出错！'
                    );
                }
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有父级分类！'
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
        $id = Yii::app()->request->getParam('id',0);
        //$model=$this->loadModel($id);
        $model=Trees::model()->findByPk($id);
        if (!$model) {
            $model = new Trees();
        }

        $parent = Yii::app()->request->getParam('pid',0);
        $title = Yii::app()->request->getParam('title','');
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'ok'
        );
        $ret['data'] = array();

        if ($parent == 0) {
            $oldvalue = $model->attributes;
            $model->title = $title;
            $model->content = $title;
            $model->pid = 0;
            $model->ishidden = 0;
            if ($model->save()) {
                $ret['data'] = array(
                    'id'=>$model->id,
                    'pid'=>0,
                    'title'=>$model->title
                );
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$username,
                    'content'=>$username."修改一级树形结构",
                    'oldvalue'=>json_encode($oldvalue),
                    'newvalue'=>json_encode($model->attributes)
                );
                $this->addlog($log);
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'保存结构出错！'
                );
            }
        }else{
            $sql = "select * from {{trees}} where id='".$parent."'";
            $row = Yii::app()->db->createCommand($sql)->queryRow();
            if ($row) {
                $oldvalue = $model->attributes;
                $model->title = $title;
                $model->content = $title;
                $model->pid = $row['id'];
                $model->ishidden = 0;
                if ($model->save()) {
                    $ret['data'] = array(
                        'id'=>$model->id,
                        'pid'=>$model->pid,
                        'title'=>$model->title
                    );
                    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                    $log = array(
                        'type'=>2,
                        'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                        'username'=>$username,
                        'content'=>$username."修改一级树形结构",
                        'oldvalue'=>json_encode($oldvalue),
                        'newvalue'=>json_encode($model->attributes)
                    );
                    $this->addlog($log);
                }else{
                    $ret['response'] = array(
                        'code'=>-1,
                        'msg'=>'保存结构出错！'
                    );
                }
            }else{
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'没有父级分类！'
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
        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        $id = Yii::app()->request->getParam('id',0);
        $leaf = Yii::app()->request->getParam('leaf',0);
        if ($id  == 0) {
            $ret['response'] = array(
                'code' => -1,
                'msg' => '删除级别不能为空！'
            );
        }
        $model = Trees::model()->findByPk($id);
        if ($model) {
            if ($model->pid !=0) {
                $sql = "update {{trees}} set pid=".$model->pid." where pid=".$model->id;
                $exe = Yii::app()->db->createCommand($sql)->execute();
                if ($exe && $leaf) {
                    $sql = "select * from {{trees}} where id=".$model->pid;
                    $row = Yii::app()->db->createCommand($sql)->queryRow();
                    $sql = "update {{site}} set aid=".$row['id'].", area='".$row['title']."'";
                    Yii::app()->db->createCommand($sql)->execute();
                }

            }
            $oldvalue = $model->attributes;
            $result = $model->delete();
            if (!$result) {
                $ret['response'] = array(
                    'code' => -1,
                    'msg' => '删除失败！'
                );
            }else{
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'test';
                $log = array(
                    'type'=>2,
                    'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                    'username'=>$username,
                    'content'=>$username."删除了一个树形结构",
                    'oldvalue'=>json_encode($oldvalue),
                    'newvalue'=>''
                );
                $this->addlog($log);
            }
        }else{
            $ret['response'] = array(
                'code' => -1,
                'msg' => '没有该级别的数据！'
            );
        }

        echo json_encode($ret);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
	    //xl
	    $user_info = GeneralLogic::isWatcher($_SESSION['uid']);
	    
        $trees = Yii::app()->db->createCommand()
            ->select('id,pid,title')
            ->from('{{trees}}')
            ->order('id asc')
            ->queryAll();
        if(!empty($user_info['areas'])){
            foreach($trees as $k => $tree){
                if(!in_array($tree['id'], $user_info['areas'])){
                   unset($trees[$k]);
                }
            }
            
            $trees = empty($trees) ? array() : array_values($trees);
        }

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        //if (count($trees)>1) {
        //    $tree = $this->generateTree($trees);
        //    $ret['data']['list'] = $tree;
        //}else if ($trees) {
        //    $ret['data']['list'] = $trees;
        //
        //}
        $levels = Yii::app( )->config->get( 'tree_level');
        $ret['data']['levels'] = $levels;
        if ($trees) {
            //$tree = $this->generateTree($trees);
            $ret['data']['list'] = $trees;
        }else{
            $ret['data']['list'][] = array(
                'id'=>1,
                'pid'=>0,
                'title'=>'全国'
            );
            //$ret['response'] = array(
            //    'code' => -1,
            //    'msg' => '没有该级别的数据！'
            //);
        }
        echo json_encode($ret);
	}

    public function actionGetnav()
    {
        $keyword = Yii::app()->request->getParam('key','');
        $areas = "select area from my_sysuser where id = ".$_SESSION["uid"];
        $auths = Yii::app()->db->createCommand($areas)->queryScalar();
        $where = "";
        if($auths == "*"){
            $sql = "select id,pid, title
                from my_trees";
        }else{
            $areaids = implode(",",explode(",", $auths));
            $sql = "select id,pid, title
                from my_trees
                where id in (".$areaids.")";
            $where .= " and m.aid in (".$areaids.")";
        }
       // var_dump($sql);
        $trees = Yii::app()->db->createCommand($sql)
            ->queryAll();

        $ret['response'] = array(
            'code' => 0,
            'msg' => 'ok'
        );
        $ret['data'] = array();
        if ($trees) {
            //$tree = $this->generateTree($trees);
            foreach ($trees as $key => $value) {
                $value['leveltype'] = 1;
                $ret['data']['list'][] = $value;
            }
            $sql = "";
            if ($keyword) {
                $sql ="
                    select m.*, a.* from tb_station_module as a,my_site as m
                    where m.is_checked = 1 
                    and 
                    a.sn_key = m.serial_number
                    and m.site_name like '%".$keyword."%'
                ".$where;

            }else{
                $sql ="
                    select m.*, a.* from tb_station_module as a,my_site as m
                    where m.is_checked = 1 
                    and 
                    a.sn_key = m.serial_number
                ".$where;
            }

            $site = Yii::app()->bms->createCommand($sql)->queryAll();
            $temp = $sids = array();

            if ($site) {
                $i = 0;
                foreach ($site as $key => $value) {
                    $i++;
                    $data = array();
                    $data['id'] = substr($value['serial_number'],0,-4);
                    $data['pid'] = $value['aid'];
                    $data['title'] = $value['site_name'] .$value['sid'];
                    $data['is_checked'] = $value['is_checked'];
                    $data['leveltype'] = 2;
                    if($value['site_name']){
                        $ret['data']['list'][] = $data;

                        $sids[] = $value['serial_number']/10000;
                    }
                }
                if ($i) {
                    // group
                    $group = Yii::app()->bms->createCommand()
                        ->selectDistinct('gid as id,sid as pid,sn_key as title')
                        ->from('{{group_module}}')
                        ->where('floor(sn_key/10000) in ('.implode(',',$sids).')')
                        ->order('sid asc')
                        ->queryAll();
                    if ($group) {
                        foreach ($group as $key => $value) {
                            $data = array();
                            $data['leveltype'] = 3;
                            $data['id'] = substr($value['title'],0,-2);
                            $data['pid'] = substr($value['title'],0,-4);
                            //$data['title'] = $value['title'];
                            $data['title'] = '组'.substr($data['id'],-2);
                            $ret['data']['list'][] = $data;
                        }
                    }

                    $battery = Yii::app()->bms->createCommand()
                        ->selectDistinct('mid as id,gid as pid,sn_key as title')
                        ->from('{{battery_module}}')
                        ->where('floor(sn_key/10000) in ('.implode(',',$sids).')')
                        ->order('id asc')
                        ->queryAll();
                    if ($battery) {
                        foreach ( $battery as $key => $value) {
                            $data = array();
                            $data['leveltype'] = 4;
                            $data['id'] = $value['title'];
                            $data['pid'] = substr($value['title'],0,-2);
                            //$data['title'] = $value['title'];
                            $data['title'] = '电池'.substr($value['title'],-2);;
                            $ret['data']['list'][] = $data;
                        }
                        //array_push($ret['data']['list'], $battery);
                    }
                }
            }
        }else{
            $ret['data']['list'][] = array(
                'leveltype'=>1,
                'id'=>1,
                'pid'=>0,
                'title'=>'全国'
            );
        }
        echo json_encode($ret);
    }
    public function generateTree($items,$pid = 0)
    {
        $tree = array();
        //第一步，将所有的分类id作为数组key,并创建children单元
        foreach($items as $category){
            $tree[$category['id']] = $category;
            $tree[$category['id']]['children'] = array();
        }
        //第二步，利用引用，将每个分类添加到父类children数组中，这样一次遍历即可形成树形结构。
        foreach ($tree as $key=>$value) {
            if ($value['pid'] != 0) {
                $tree[$value['pid']]['children'][] = &$tree[$key];
            }
        }
        foreach ($tree as $k => $v) {
            if ($v['pid'] == $pid) {
                $data[] = $v;
            }
        }
        return $data;
    }
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Trees('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Trees']))
			$model->attributes=$_GET['Trees'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Trees the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Trees::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Trees $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='trees-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
