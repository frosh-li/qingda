<?php

class ParamController extends Controller
{
	public function actionIndex()
	{
        $refresh = Yii::app()->request->getParam('refresh','');
        $collection  = Yii::app()->request->getParam('collection','');
        $resistance  = Yii::app()->request->getParam('resistance','');
        $dismap  = Yii::app()->request->getParam('dismap','');
        $ret['response'] = array(
            'code'=>0,
            'msg'=>'设置参数成功！'
        );
        $ret['data'] = array();
        $i = 0;
        if ($refresh != '') {
            $i++;
            Yii::app( )->config->set( 'refresh', $refresh );
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
            $ret['data'] = array(
                'dismap' => $dismap
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
            $array = array('refresh','collection','resistance','dismap');
            foreach ($array as $key => $k) {
                $v = Yii::app()->config->get($k);
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