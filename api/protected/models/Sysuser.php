<?php

/**
 * This is the model class for table "{{sysuser}}".
 *
 * The followings are the available columns in table '{{sysuser}}':
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $gender
 * @property string $password
 * @property string $salt
 * @property integer $role
 * @property string $phone
 * @property string $email
 * @property string $postname
 * @property string $location
 * @property string $site
 * @property string $profile
 * @property string $last_login_time
 * @property string $create_time
 * @property string $update_time
 */
class Sysuser extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sysuser the static model class
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
		return '{{sysuser}}';
	}
    /**
     * Prepares posttime  attributes before performing validation.
     */
    protected function beforeValidate() {

        if ($this->isNewRecord) {
            $this->create_time = $this->update_time = date('Y-m-d H:i:s');
        }else{
            $this->update_time = date('Y-m-d H:i:s');
        }
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
			array('username, name, gender, password, salt, role, phone, email, postname, location, site, create_time, update_time', 'required'),
			array('role', 'numerical', 'integerOnly'=>true),
			array('username, password, email', 'length', 'max'=>128),
			array('name', 'length', 'max'=>20),
            array('username', 'unique'),
			array('gender, salt', 'length', 'max'=>10),
			array('phone, postname', 'length', 'max'=>50),
			array('location, site', 'length', 'max'=>255),
			array('profile', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, name, gender, password, salt, role, phone, email, postname, location, site, profile, last_login_time, create_time, update_time', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'name' => 'Name',
			'gender' => 'Gender',
			'password' => 'Password',
			'salt' => 'Salt',
			'role' => 'Role',
			'phone' => 'Phone',
			'email' => 'Email',
			'postname' => 'Postname',
			'location' => 'Location',
			'site' => 'Site',
			'profile' => 'Profile',
			'last_login_time' => 'Last Login Time',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
		);
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
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('postname',$this->postname,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('site',$this->site,true);
		$criteria->compare('profile',$this->profile,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}