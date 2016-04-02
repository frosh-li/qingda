<?php

/**
 * This is the model class for table "{{bms_info}}".
 *
 * The followings are the available columns in table '{{bms_info}}':
 * @property integer $id
 * @property string $bms_company
 * @property string $bms_device_addr
 * @property string $bms_postcode
 * @property string $bms_url
 * @property string $bms_tel
 * @property string $bms_phone
 * @property string $bms_service_phone
 * @property string $bms_service_name
 * @property string $bms_service_url
 * @property string $bms_version
 * @property string $bms_update_mark
 * @property string $bms_mark
 * @property string $modify_time
 */
class BmsInfo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BmsInfo the static model class
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
		return '{{bms_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bms_company, bms_device_addr, bms_postcode, bms_url, bms_tel, bms_phone, bms_service_phone, bms_service_name, bms_service_url, bms_version, bms_update_mark, bms_mark, modify_time', 'required'),
			array('bms_company, bms_device_addr', 'length', 'max'=>255),
			array('bms_postcode, bms_tel, bms_phone, bms_service_phone, bms_version', 'length', 'max'=>20),
			array('bms_url, bms_service_url', 'length', 'max'=>150),
			array('bms_service_name', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, bms_company, bms_device_addr, bms_postcode, bms_url, bms_tel, bms_phone, bms_service_phone, bms_service_name, bms_service_url, bms_version, bms_update_mark, bms_mark, modify_time', 'safe', 'on'=>'search'),
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
			'bms_company' => 'Bms Company',
			'bms_device_addr' => 'Bms Device Addr',
			'bms_postcode' => 'Bms Postcode',
			'bms_url' => 'Bms Url',
			'bms_tel' => 'Bms Tel',
			'bms_phone' => 'Bms Phone',
			'bms_service_phone' => 'Bms Service Phone',
			'bms_service_name' => 'Bms Service Name',
			'bms_service_url' => 'Bms Service Url',
			'bms_version' => 'Bms Version',
			'bms_update_mark' => 'Bms Update Mark',
			'bms_mark' => 'Bms Mark',
			'modify_time' => 'Modify Time',
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
		$criteria->compare('bms_company',$this->bms_company,true);
		$criteria->compare('bms_device_addr',$this->bms_device_addr,true);
		$criteria->compare('bms_postcode',$this->bms_postcode,true);
		$criteria->compare('bms_url',$this->bms_url,true);
		$criteria->compare('bms_tel',$this->bms_tel,true);
		$criteria->compare('bms_phone',$this->bms_phone,true);
		$criteria->compare('bms_service_phone',$this->bms_service_phone,true);
		$criteria->compare('bms_service_name',$this->bms_service_name,true);
		$criteria->compare('bms_service_url',$this->bms_service_url,true);
		$criteria->compare('bms_version',$this->bms_version,true);
		$criteria->compare('bms_update_mark',$this->bms_update_mark,true);
		$criteria->compare('bms_mark',$this->bms_mark,true);
		$criteria->compare('modify_time',$this->modify_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}