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
    public function isLogin() {
    }
}
