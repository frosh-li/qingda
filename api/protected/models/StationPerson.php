<?php

/**
 * This is the model class for table "{{station_person}}".
 *
 * The followings are the available columns in table '{{station_person}}':
 * @property string $id
 * @property integer $sid
 * @property string $Operater
 * @property string $Operater_cellphone
 * @property string $Alarm_SMS_receive_cellphone
 * @property string $Alarm_SMS_receive_email
 * @property integer $create_time
 * @property integer $update_time
 */
class StationPerson extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StationPerson the static model class
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
		return '{{station_person}}';
	}
    /**
     * Prepares posttime  attributes before performing validation.
     */
    protected function beforeValidate() {

        if ($this->isNewRecord) {
            $this->create_time = $this->update_time = time();
        }else{
            $this->update_time = time();
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
			array('sid, Operater, Operater_cellphone, Alarm_SMS_receive_cellphone, Alarm_SMS_receive_email, create_time, update_time', 'required'),
			array('sid, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('Operater, Operater_cellphone, Alarm_SMS_receive_cellphone, Alarm_SMS_receive_email', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sid, Operater, Operater_cellphone, Alarm_SMS_receive_cellphone, Alarm_SMS_receive_email, create_time, update_time', 'safe', 'on'=>'search'),
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
			'sid' => 'Sid',
			'Operater' => 'Operater',
			'Operater_cellphone' => 'Operater Cellphone',
			'Alarm_SMS_receive_cellphone' => 'Alarm Sms Receive Cellphone',
			'Alarm_SMS_receive_email' => 'Alarm Sms Receive Emal',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('sid',$this->sid);
		$criteria->compare('Operater',$this->Operater,true);
		$criteria->compare('Operater_cellphone',$this->Operater_cellphone,true);
		$criteria->compare('Alarm_SMS_receive_cellphone',$this->Alarm_SMS_receive_cellphone,true);
		$criteria->compare('Alarm_SMS_receive_email',$this->Alarm_SMS_receive_email,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}