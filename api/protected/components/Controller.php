<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	public $topmenus = array();
	public $keywords = "";
	public $description = "";
	public $searchkey = "";
    public $page = 1;
    public $count = 10;
	/**
	 * @var Session
	 */
	private $session = null;

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init(){
		//error_reporting(0);
		defined('DS') or define('DS',DIRECTORY_SEPARATOR);
		parent::init();
		$this->session = new Session();
		$this->session->open();
	}

	/**
	 * @return Session
	 */
	protected function getSession() {
		return $this->session;
	}
	/**
	*先进行网站开关的判断
	**/
	protected function beforeAction($action)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		    exit; // finish preflight CORS requests here
		}
		if (!YII_DEBUG){
			Yii::app( )->db->schemaCachingDuration = 86400;
		}
		if (empty($this->pageTitle)){
			$this->pageTitle = Yii::app()->config->get("title");
		}
		if (empty($this->keywords)){
			$this->keywords = Yii::app()->config->get("keywords");
		}
		if (empty($this->description)){
			$this->description = Yii::app()->config->get( "description" );
		}
		if (Yii::app( )->config->get( "site_off" ) == "1"){
			echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n<title>本站例行维护，暂时停止服务，请稍后回来……</title>\r\n</head>\r\n\r\n<body>\r\n本站例行维护，暂时停止服务，请稍后回来……\r\n</body>\r\n</html>";
			Yii::app()->end();
		}
        if (!YII_DEBUG) {
            if (!isset($_SESSION['uid'])) {
                $ret['response'] = array(
                    'code'=>-1,
                    'msg'=>'未登录！'
                );
                $ret['data'] = array();
                echo json_encode($ret);
                Yii::app()->end();
            }
        }
		return parent::beforeaction($action);
	}
    /**
     * [checkPageCount description]
     * @return [type] [description]
     */
    public function checkPageCount()
    {
        if ($this->page<=0) {
            throw new CHttpException(13,'need page number');
        }
        if ($this->count<=0) {
            throw new CHttpException(14,'need count number');
        }
    }

    public function getErrors($errors)
    {
        $str = '';
        foreach ($errors as $key => $value) {
            $str .= $value[0]."<br>";
        }
        return $str;
    }
    /**
     * [setPageCount description]
     */
    public function setPageCount($count=10)
    {
        $this->page = Yii::app()->request->getParam('page',1);
        $this->count = Yii::app()->request->getParam('pageSize',$count);
        $this->checkPageCount();
    }

    public function addlog($log)
    {
        $log['modify_time'] = date("Y-m-d H:i:s");
        $insql = Utils::buildInsertSQL($log);
        $sql = "insert into {{action_log}} ".$insql;
        $ret = Yii::app()->db->createCommand($sql)->execute();
        return $ret;
    }

	protected function ajaxReturn($code = 0, $msg = '', $data = array()) {
		Utils::ajaxReturn($code, $msg, $data);
		Yii::app()->end();
	}
}
