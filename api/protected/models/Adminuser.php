<?php

/**
 * This is the model class for table "{{adminuser}}".
 *
 * The followings are the available columns in table '{{adminuser}}':
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $department
 * @property string $password
 * @property string $salt
 * @property integer $role
 * @property string $catalog
 * @property string $email
 * @property string $profile
 * @property string $ipaddress
 * @property string $last_login_time
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 */
class Adminuser extends CActiveRecord
{
	public $password_org;
	public $password_repeat;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Adminuser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{adminuser}}';
	}
	/**
     * Prepares create_time, create_user_id, update_time and
     * update_user_ id attributes before performing validation.
     */
    protected function beforeValidate() {
 
        if ($this->isNewRecord) {
            // set the create date, last updated date
            // and the user doing the creating
            $this->create_time = $this->update_time = date( 'Y-m-d H:i:s', time() );
 			$this->last_login_time = date( 'Y-m-d H:i:s', time() );
            $this->create_user_id = $this->update_user_id = Yii::app()->user->id;
        } else {
            //not a new record, so just set the last updated time
            //and last updated user id
            $this->update_time = date( 'Y-m-d H:i:s', time() );
            $this->update_user_id = 1;
 
        }
        $this->ipaddress = Utils::get_real_ip();
        return parent::beforeValidate();
    }
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username,name,department, password, role, email, ipaddress, last_login_time, create_time, create_user_id, update_time', 'required'),
			array('role, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('password_repeat', 'required','message'=>'确认密码必填','on'=>'changepwd'),
			array('password_repeat', 'required','message'=>'确认密码必填','on'=>'update'),
			array('username, password, email', 'length', 'max'=>128),
			array('name', 'length', 'max'=>20),
			array('department', 'length', 'max'=>100),
			array('salt', 'length', 'max'=>10),
			array('catalog,pid,cid', 'length', 'max'=>50),
			array('ipaddress', 'length', 'max'=>15),
			array('profile', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, name, department, password, salt, role, catalog, email, profile, ipaddress, last_login_time, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '登录用户名',
			'name' => '姓名',
			'department' => '部门',
			'password' => '密码',
			'salt' => '干扰码',
			'role' => '角色',
			'password_org' => '原密码',
			'password' => '用户密码',
			'password_repeat' => '确认密码',
			'catalog' => '授权主分类',
			'pid' => '授权二级分类',
			'cid' => '授权三级分类',
			'email' => '邮箱',
			'profile' => '简介',
			'ipaddress' => '登录IP',
			'last_login_time' => 'Last Login Time',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
		);
	}
	//文件存储role 数组
	public function getAllRoleFile()
	{
		return array(
				array('id'=>ROLE_ADMIN,'name'=>'管理员'),
				array('id'=>ROLE_CHANNLADMIN,'name'=>'栏目管理'),
				// array('id'=>ROLE_INFOADMIN,'name'=>'信息人员'),
			);
	}
	//获取文字
	public function getRoleFileText()
	{
		$role = $this->getAllRoleFile();
		foreach ($role as $key => $value) {
			if ($value['id']== $this->role) {
				return $value['name'];
			}
		}
	}
	public function getRoleFileFilter()
	{
		$roles = $role = $this->getAllRoleFile();
		$ret = array();
		if ($roles) {
			foreach ($roles as $key => $value) {
				$ret[$value['id']] = $value['name'];
			}
		}
		return $ret;
	}
	//role 数组
	public function getAllRole()
	{
		$roles = Yii::app()->db->createCommand()
				    ->select('role,typename')
				    ->from('{{admintype}}')
				    ->queryAll();
		$ret = array();
		if ($roles) {
			foreach ($roles as $key => $value) {
				$ret[] = array('id'=>$value['role'],'name'=>$value['typename']);
			}
		}
		return $ret;
	}
	public function getRoleFilter()
	{
		$roles = Yii::app()->db->createCommand()
				    ->select('role,typename')
				    ->from('{{admintype}}')
				    ->queryAll();
		$ret = array();
		if ($roles) {
			foreach ($roles as $key => $value) {
				$ret[$value['role']] = $value['typename'];
			}
		}
		return $ret;
	}
	//分类 数组
	public function getAllCate()
	{
		$cate = Yii::app()->db->createCommand()
				    ->select('id,title')
				    ->from('{{channels}}')
				    ->where('pid=0')
				    ->queryAll();
		$ret = array(array('id'=>0,'name'=>'-所有栏目-'));
		if ($cate) {
			foreach ($cate as $key => $value) {
				$ret[] = array('id'=>$value['id'],'name'=>$value['title']);
			}
		}
		return $ret;
	}
	
	//获取文字
	public function getRoleText()
	{
		$role = $this->getRoleFilter();
	    return isset($role[$this->role]) ? $role[$this->role] :"";
	}
    /**
      * perform one-way encryption on the password before we store it in the database
    */
    // protected function afterValidate() {
    //     parent::afterValidate();
    //     $this->password = $this->encrypt($this->password);
    // }
	//加密算法
    public function encrypt($value) {
        return md5($value);
    }
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('catalog',$this->catalog,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('profile',$this->profile,true);
		$criteria->compare('ipaddress',$this->ipaddress,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
	            'pageSize'=>20,//设置每页显示20条
	        ),
			'criteria'=>$criteria,
		));
	}
}