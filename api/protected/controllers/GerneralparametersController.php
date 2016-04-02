<?php

class GerneralparametersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

    public function actionJudge()
    {
        $key = Yii::app()->request->getParam('key','');
        $value = Yii::app()->request->getParam('value','');
        $sql = "select * from {{program_gerneral_parameters}} where id=1";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row[$key] == $value) {
            $ret['response'] = array(
                'code' => 0,
                'msg' => '密码正确！'
            );
            $ret['data'] = array();
        }else{
            $ret['response'] = array(
                'code' => 0,
                'msg' => '密码错误！'
            );
            $ret['data'] = array();
        }
        echo json_encode($ret);
    }
    public function actionGetkey()
    {
        $key = Yii::app()->request->getParam('key','');
        $sql = "select * from {{program_gerneral_parameters}} where id=1";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row) {
            $ret['response'] = array(
                'code' => 0,
                'msg' => 'ok'
            );
            $ret['data'] = array($key=>$row[$key]);
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该参数！'
            );
            $ret['data'] = array();
        }
        echo json_encode($ret);
    }
    public function actionGetall()
    {
        $id= 1;
        $model=$this->loadModel($id);

        if ($model) {
            $ret['response'] = array(
                'code' => 0,
                'msg' => 'ok'
            );
            $ret['data'] = array(
                'password' => $model->password,
                'time_for_db_write_interval' => $model->time_for_db_write_interval,
                'b_station_basic_infor_changed' => $model->b_station_basic_infor_changed,
                'N_histroy_average_number' => $model->N_histroy_average_number,
                'station_delta_I_write_history_db' => $model->station_delta_I_write_history_db,
                'battery_delta_U_write_history_db' => $model->battery_delta_U_write_history_db,
                'battery_delta_R_write_history_db' => $model->battery_delta_R_write_history_db,
                'battery_delta_T_write_history_db' => $model->battery_delta_T_write_history_db,
                'group_delta_I_write_history_db' => $model->group_delta_I_write_history_db,
                'group_delta_U_write_history_db' => $model->group_delta_U_write_history_db,
                'group_delta_T_write_history_db' => $model->group_delta_T_write_history_db
            );
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'没有该参数！'
            );
            $ret['data'] = array();
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
        $id= 1;
		$model=$this->loadModel($id);
        $password = Yii::app()->request->getParam('password','');
        if ($password) {
            $model->password = $password;
        }
        if ($model->save()) {
            $ret['response'] = array(
                'code'=>0,
                'msg'=>'ok'
            );
            $ret['data'] = array(
                'password'=>$model->password,
                'time_for_db_write_interval'=>$model->time_for_db_write_interval,
                'b_station_basic_infor_changed' =>$model->b_station_basic_infor_changed,
                'N_histroy_average_number'=>$model->N_histroy_average_number,
                'station_delta_I_write_history_db'=>$model->station_delta_I_write_history_db,
                'battery_delta_U_write_history_db'=>$model->battery_delta_U_write_history_db,
                'battery_delta_R_write_history_db'=>$model->battery_delta_R_write_history_db,
                'battery_delta_T_write_history_db'=>$model->battery_delta_T_write_history_db,
                'group_delta_I_write_history_db'=>$model->group_delta_I_write_history_db,
                'group_delta_U_write_history_db'=>$model->group_delta_U_write_history_db,
                'group_delta_T_write_history_db'=>$model->group_delta_T_write_history_db
            );
        }else{
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'保存设置失败！'
            );
            $ret['data'] = array();
        }
        echo json_encode($ret);
	}
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return GerneralParameters the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=GerneralParameters::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}
