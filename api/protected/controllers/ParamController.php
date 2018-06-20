<?php

class ParamController extends Controller
{
	public function actionIndex()
	{
        $refresh = Yii::app()->request->getParam('refresh','');
        $refreshall = Yii::app()->request->getParam('refreshall','');
        $collection  = Yii::app()->request->getParam('collection','');
        $resistance  = Yii::app()->request->getParam('resistance','');
        $dismap  = Yii::app()->request->getParam('dismap','');
        $sms_on_off  = Yii::app()->request->getParam('sms_on_off','');
        $email_on_off  = Yii::app()->request->getParam('email_on_off','');
        $light_on_off  = Yii::app()->request->getParam('light_on_off','');
        $voice_on_off  = Yii::app()->request->getParam('voice_on_off','');
        // echo $refreshall;exit;
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'设置参数成功！'
        );
        $ret['data'] = array();
        $i = 0;
        if ($refreshall != ''){
            $i ++;
            $sql = "update my_sysuser set refresh = 20";
            Yii::app()->bms->createCommand($sql)->execute();
            $log = array(
                'type'=>2,
                'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                'username'=>$_SESSION['username'],
                'content'=>$_SESSION['username']."调整了全部的刷新时间",
                'oldvalue'=>'',
                'newvalue'=>$refreshall
            );
            $this->addlog($log);
            $ret['data'] = array(
                'refreshall'=>$refreshall
            );
        }
        if ($refresh != '') {
            $i++;
            // Yii::app( )->config->set( 'refresh', $refresh );
            $sql = "update my_sysuser set refresh = $refresh where id = $_SESSION[uid]";
            Yii::app()->bms->createCommand($sql)->execute();
            $log = array(
                'type'=>2,
                'uid'=>isset($_SESSION['uid']) ? $_SESSION['uid'] : 1,
                'username'=>$_SESSION['username'],
                'content'=>$_SESSION['username']."调整了刷新时间",
                'oldvalue'=>'',
                'newvalue'=>$refresh
            );
            $this->addlog($log);
            $ret['data'] = array(
                'refresh'=>$refresh
            );
        }
        if ($collection != '') {
            $i++;
            Yii::app()->config->set('collection', $collection);
            $ret['data'] = array(
                'collection' => $collection
            );
        }
        if ($resistance != '') {
            $i++;
            Yii::app()->config->set('resistance', $resistance);
            $ret['data'] = array(
                'resistance' => $resistance
            );
        }
        if ($dismap != '') {
            $i++;
            Yii::app()->config->set('dismap', $dismap);
            Yii::app()->config->set('sms_on_off', $sms_on_off);
            Yii::app()->config->set('email_on_off', $email_on_off);
            Yii::app()->config->set('light_on_off', $light_on_off);
            Yii::app()->config->set('voice_on_off', $voice_on_off);
            $ret['data'] = array(
                'dismap' => $dismap,
                'sms_on_off' => $sms_on_off,
                'email_on_off' => $email_on_off,
                'light_on_off' => $light_on_off,
                'voice_on_off' => $voice_on_off
            );
        }


        if ($i ==0 ) {
            $ret['response'] = array(
                'code'=>-1,
                'msg'=>'设置失败！'
            );
            $ret['data'] = array();
        }
        echo json_encode($ret);

	}

    public function actionGetpara()
    {
        $key = Yii::app()->request->getParam('key','');
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'获取参数成功！'
        );
        $ret['data'] = array();
        if ($key) {
            $value = Yii::app()->config->get($key);
            $ret['data'] = array(
                $key=>$value
            );
        }else{
            $array = array('refresh','collection','resistance','dismap','sms_on_off','email_on_off','light_on_off','voice_on_off');
            foreach ($array as $key => $k) {
                if ($k == 'refresh'){
                    $sql = "select refresh from my_sysuser where id = $_SESSION[uid]";
                    $v = Yii::app()->bms->createCommand($sql)->queryScalar();
                }else{
                    $v = Yii::app()->config->get($k);
                }
                $ret['data'][$k] = $v;
            }
        }
        echo json_encode($ret);

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