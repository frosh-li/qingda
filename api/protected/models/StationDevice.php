<?php

/**
 * This is the model class for table "{{station_device}}".
 *
 * The followings are the available columns in table '{{station_device}}':
 * @property integer $id
 * @property integer $sid
 * @property string $Supervisory_Device_name
 * @property string $Supervisory_Device_fun
 * @property string $Supervisory_Device_Factory_Name
 * @property string $Supervisory_Device_Factory_Address
 * @property string $Supervisory_Device_Factory_PostCode
 * @property string $Supervisory_Device_Factory_website
 * @property string $Supervisory_Device_Factory_Technology_cable_phone
 * @property string $Supervisory_Device_Factory_Technology_cellphone
 * @property integer $create_time
 * @property integer $update_time
 */
class StationDevice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StationDevice the static model class
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
		return '{{station_device}}';
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
			array('sid, Supervisory_Device_name, Supervisory_Device_fun, create_time, update_time', 'required'),
			array('sid, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('Supervisory_Device_name', 'length', 'max'=>100),
			array('Supervisory_Device_fun', 'length', 'max'=>255),
			array('Supervisory_Device_Factory_Name, Supervisory_Device_Factory_Address, Supervisory_Device_Factory_PostCode, Supervisory_Device_Factory_website, Supervisory_Device_Factory_Technology_cable_phone, Supervisory_Device_Factory_Technology_cellphone', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sid, Supervisory_Device_name, Supervisory_Device_fun, Supervisory_Device_Factory_Name, Supervisory_Device_Factory_Address, Supervisory_Device_Factory_PostCode, Supervisory_Device_Factory_website, Supervisory_Device_Factory_Technology_cable_phone, Supervisory_Device_Factory_Technology_cellphone, create_time, update_time', 'safe', 'on'=>'search'),
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
			'Supervisory_Device_name' => 'Supervisory Device Name',
			'Supervisory_Device_fun' => 'Supervisory Device Fun',
			'Supervisory_Device_Factory_Name' => 'Supervisory Device Factory Name',
			'Supervisory_Device_Factory_Address' => 'Supervisory Device Factory Address',
			'Supervisory_Device_Factory_PostCode' => 'Supervisory Device Factory Post Code',
			'Supervisory_Device_Factory_website' => 'Supervisory Device Factory Website',
			'Supervisory_Device_Factory_Technology_cable_phone' => 'Supervisory Device Factory Technology Cable Phone',
			'Supervisory_Device_Factory_Technology_cellphone' => 'Supervisory Device Factory Technology Cellphone',
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
		$criteria->compare('sid',$this->sid);
		$criteria->compare('Supervisory_Device_name',$this->Supervisory_Device_name,true);
		$criteria->compare('Supervisory_Device_fun',$this->Supervisory_Device_fun,true);
		$criteria->compare('Supervisory_Device_Factory_Name',$this->Supervisory_Device_Factory_Name,true);
		$criteria->compare('Supervisory_Device_Factory_Address',$this->Supervisory_Device_Factory_Address,true);
		$criteria->compare('Supervisory_Device_Factory_PostCode',$this->Supervisory_Device_Factory_PostCode,true);
		$criteria->compare('Supervisory_Device_Factory_website',$this->Supervisory_Device_Factory_website,true);
		$criteria->compare('Supervisory_Device_Factory_Technology_cable_phone',$this->Supervisory_Device_Factory_Technology_cable_phone,true);
		$criteria->compare('Supervisory_Device_Factory_Technology_cellphone',$this->Supervisory_Device_Factory_Technology_cellphone,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}