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
        return ($_SESSION['uid'] > 0) && ($_SESSION['username'] != '') && ($_SESSION['role'] != '');
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $role
     */
    public function register($id, $name, $role) {
        $_SESSION['uid'] = intval($id);
        $_SESSION['username'] = trim($name);
        $_SESSION['role'] = trim($role);
    }

    public function loginOut() {
        $this->getSession()->destroy();
    }
}
