<?php

/**
 * This is the model class for table "{{alarmset}}".
 *
 * The followings are the available columns in table '{{alarmset}}':
 * @property integer $id
 * @property integer $aid
 * @property string $area
 * @property string $username
 * @property string $phone
 * @property integer $phone_status
 * @property string $email
 * @property string $email_status
 * @property string $create_time
 * @property string $update_time
 */
class Alarmset extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Alarmset the static model class
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
		return '{{alarmset}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('aid, area, username, phone, phone_status, email, email_status, create_time, update_time', 'required'),
			array('aid, phone_status', 'numerical', 'integerOnly'=>true),
			array('area, email', 'length', 'max'=>50),
			array('username', 'length', 'max'=>30),
			array('phone', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, aid, area, username, phone, phone_status, email, email_status, create_time, update_time', 'safe', 'on'=>'search'),
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
			'aid' => 'Aid',
			'area' => 'Area',
			'username' => 'Username',
			'phone' => 'Phone',
			'phone_status' => 'Phone Status',
			'email' => 'Email',
			'email_status' => 'Email Status',
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
		$criteria->compare('aid',$this->aid);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('phone_status',$this->phone_status);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('email_status',$this->email_status,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}