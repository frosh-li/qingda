<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = Adminuser::model()->findByAttributes(array('username' => $this->username));
        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            if ($user->password !== $user->encrypt($this->password.$user->salt)) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } else {
                $this->_id = $user->id;
                $user->ipaddress = Utils::get_real_ip();
                $user->update_user_id = 1;
                $user->password_repeat = 'repeat';
                $user->name == '' ? $user->name = 'name' : $user->name = $user->name;
                $user->department == '' ? $user->department = 'department' : $user->department = $user->department;
                $user->last_login_time = date('Y-m-d H:i:s');
                if ($user->save()) {
                    $this->setState('lastLoginTime', $user->last_login_time);
                    $this->setState('role', $user->role);
                    $this->setState('catalog', $user->catalog);
                    $this->setState('pid', $user->pid);
                    $this->setState('cid', $user->cid);
                    $this->errorCode = self::ERROR_NONE;
                }else{
                    YII_DEBUG && var_dump($user->getErrors());
                    $this->errorCode = self::ERROR_USERNAME_INVALID;
                }
            }
        }
        return!$this->errorCode;
	}
	//获得用户id
    public function getId() {
        return $this->_id;
    }
}