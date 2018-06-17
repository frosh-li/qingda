<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class LController extends CController
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
	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init(){
		//error_reporting(0);
		defined('DS') or define('DS',DIRECTORY_SEPARATOR);
		parent::init();
	}
	/**
	*先进行网站开关的判断
	**/
	protected function beforeAction($action)
	{
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
		return parent::beforeaction($action);
	}
    public function addlog($log)
    {
		$log['modify_time'] = date("Y-m-d H:i:s");
		$log['ipaddress'] = $this->get_ip();
        $insql = Utils::buildInsertSQL($log);
        $sql = "insert into {{action_log}} ".$insql;
        $ret = Yii::app()->db->createCommand($sql)->execute();
        return $ret;
	}
	
	function get_ip()
	{
	  $ip=false;
	  if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	  }
	  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
		for ($i = 0; $i < count($ips); $i++) {
		  if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
			$ip = $ips[$i];
			break;
		  }
		}
	  }
	  $ip = ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	  if ($ip == '::1') $ip = '127.0.0.1';
	  return $ip;
	}
}