<?php

class Session
{
    /**
     * @var CHttpSession
     */
    private $_session = null;

    /**
     * @return CHttpSession
     */
    protected function getSession() {
        if (null == $this->_session) {
            $this->_session = new CHttpSession();
        }

        return $this->_session;
    }

    public function open() {
        $this->getSession()->open();
    }

    /**
     * @return bool
     */
    public function isLogin() {
        $uid        = $this->getUid();
        $userName   = $this->getUserName();
        $role       = $this->getRole();

        return ($uid > 0) && ($userName != '') && ($role != '');
    }

    /**
     * get uid
     * @return int
     */
    public function getUid() {
        return isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
    }

    /**
     * get role
     *
     * @return string
     */
    public function getRole() {
        return isset($_SESSION['role']) ? $_SESSION['role'] : '';
    }

    /**
     * get area
     *
     * @return string
     */
    public function getArea() {
        return isset($_SESSION['area']) ? $_SESSION['area'] : '';
    }

    /**
     * get user name
     * @return string
     */
    public function getUserName() {
        return isset($_SESSION['username']) ? $_SESSION['username'] : '';
    }

    /**
     * @return string
     */
    public function getLoginTime() {
        date_default_timezone_set('Asia/Shanghai');
        return isset($_SESSION['loginTime']) ? $_SESSION['loginTime'] : '';
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $role
     */
    public function register($id, $name, $role, $area) {
        date_default_timezone_set('Asia/Shanghai');
        $_SESSION['uid'] = intval($id);
        $_SESSION['username'] = trim($name);
        $_SESSION['role'] = trim($role);
        $_SESSION['loginTime'] = date('Y-m-d H:i:s');
        $_SESSION['area'] = trim($area);
    }

    public function registerEnterTime() {
        date_default_timezone_set('Asia/Shanghai');
        if (!isset($_SESSION['enterTime'])) {
            $_SESSION['enterTime'] = date('Y-m-d H:i:s');
        }
    }

    /**
     * @return bool|string
     */
    public function getEnterTime() {
        date_default_timezone_set('Asia/Shanghai');
        return isset($_SESSION['enterTime']) ? $_SESSION['enterTime'] : date('Y-m-d H:i:s');
    }

    public function loginOut() {
        $this->getSession()->destroy();
    }
}
