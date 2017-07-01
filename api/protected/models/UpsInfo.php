<?php

/**
 * This is the model class for table "{{ups_info}}".
 *
 * The followings are the available columns in table '{{ups_info}}':
 * @property integer $id
 * @property integer $sid
 * @property string $ups_factory
 * @property string $ups_type
 * @property string $ups_create_date
 * @property string $ups_install_date
 * @property double $ups_power
 * @property integer $redundancy_num
 * @property double $floting_charge
 * @property double $ups_vdc
 * @property integer $ups_reserve_hour
 * @property string $ups_charge_mode
 * @property double $ups_max_charge
 * @property double $ups_max_discharge
 * @property integer $ups_period_days
 * @property integer $ups_discharge_time
 * @property double $ups_discharge_capacity
 * @property integer $ups_maintain_date
 * @property string $ups_vender_phone
 * @property string $ups_service
 * @property string $ups_service_phone
 * @property integer $create_time
 * @property integer $update_time
 */
class UpsInfo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UpsInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ups_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sid, ups_factory, create_time, update_time', 'required'),
			array('sid, redundancy_num, ups_reserve_hour, ups_period_days, ups_discharge_time, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('ups_power, floting_charge, ups_vdc, ups_max_charge, ups_max_discharge, ups_discharge_capacity', 'numerical'),
			array('ups_factory', 'length', 'max'=>255),
			array('ups_type, ups_charge_mode, ups_service', 'length', 'max'=>50),
			array('ups_vender_phone, ups_service_phone', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sid, ups_factory, ups_type, ups_create_date, ups_install_date, ups_power, redundancy_num, floting_charge, ups_vdc, ups_reserve_hour, ups_charge_mode, ups_max_charge, ups_max_discharge, ups_period_days, ups_discharge_time, ups_discharge_capacity, ups_maintain_date, ups_vender_phone, ups_service, ups_service_phone, create_time, update_time', 'safe', 'on'=>'search'),
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
			'sid' => '站id',
			'ups_factory' => 'UPS生产厂家',
			'ups_type' => 'Ups Type',
			'ups_create_date' => 'Ups Create Date',
			'ups_install_date' => 'Ups Install Date',
			'ups_power' => 'Ups Power',
			'redundancy_num' => 'Redundancy Num',
			'floting_charge' => 'Flating Charge',
			'ups_vdc' => 'Ups Vdc',
			'ups_reserve_hour' => 'Ups Reserve Hour',
			'ups_charge_mode' => 'Ups Charge Mode',
			'ups_max_charge' => 'Ups Max Charge',
			'ups_max_discharge' => 'Ups Max Discharge',
			'ups_period_days' => 'Ups Period Days',
			'ups_discharge_time' => 'Ups Discharge Time',
			'ups_discharge_capacity' => 'Ups Discharge Capacity',
			'ups_maintain_date' => 'Ups Maintain Date',
			'ups_vender_phone' => 'Ups Vender Phone',
			'ups_service' => 'Ups Service',
			'ups_service_phone' => 'Ups Service Phone',
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
		$criteria->compare('ups_factory',$this->ups_factory,true);
		$criteria->compare('ups_type',$this->ups_type,true);
		$criteria->compare('ups_create_date',$this->ups_create_date,true);
		$criteria->compare('ups_install_date',$this->ups_install_date,true);
		$criteria->compare('ups_power',$this->ups_power);
		$criteria->compare('redundancy_num',$this->redundancy_num);
		$criteria->compare('floting_charge',$this->floting_charge);
		$criteria->compare('ups_vdc',$this->ups_vdc);
		$criteria->compare('ups_reserve_hour',$this->ups_reserve_hour);
		$criteria->compare('ups_charge_mode',$this->ups_charge_mode,true);
		$criteria->compare('ups_max_charge',$this->ups_max_charge);
		$criteria->compare('ups_max_discharge',$this->ups_max_discharge);
		$criteria->compare('ups_period_days',$this->ups_period_days);
		$criteria->compare('ups_discharge_time',$this->ups_discharge_time);
		$criteria->compare('ups_discharge_capacity',$this->ups_discharge_capacity);
		$criteria->compare('ups_maintain_date',$this->ups_maintain_date);
		$criteria->compare('ups_vender_phone',$this->ups_vender_phone,true);
		$criteria->compare('ups_service',$this->ups_service,true);
		$criteria->compare('ups_service_phone',$this->ups_service_phone,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}