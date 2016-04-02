<?php

/**
 * This is the model class for table "{{group_parameter}}".
 *
 * The followings are the available columns in table '{{group_parameter}}':
 * @property string $id
 * @property integer $HaveCurrentSensor
 * @property integer $StationCurrentSensorSpan
 * @property integer $StationCurrentSensorZeroADCode
 * @property integer $OSC
 * @property integer $DisbytegeCurrentLimit
 * @property integer $bytegeCurrentLimit
 * @property integer $TemperatureHighLimit
 * @property integer $TemperatureLowLimit
 * @property integer $HumiH
 * @property integer $HumiL
 * @property integer $TemperatureAdjust
 * @property integer $HumiAdjust
 */
class GroupParameter extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GroupParameter the static model class
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
		return '{{group_parameter}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('HaveCurrentSensor, StationCurrentSensorSpan, OSC, DisbytegeCurrentLimit, bytegeCurrentLimit, TemperatureHighLimit, TemperatureLowLimit, HumiH, HumiL, TemperatureAdjust, HumiAdjust', 'numerical', 'integerOnly'=>true),
            array('StationCurrentSensorZeroADCode', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, HaveCurrentSensor, StationCurrentSensorSpan, StationCurrentSensorZeroADCode, OSC, DisbytegeCurrentLimit, bytegeCurrentLimit, TemperatureHighLimit, TemperatureLowLimit, HumiH, HumiL, TemperatureAdjust, HumiAdjust', 'safe', 'on'=>'search'),
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
			'HaveCurrentSensor' => '有无电流传感器',
			'StationCurrentSensorSpan' => '传感器量程',
			'StationCurrentSensorZeroADCode' => '传感器零位AD码',
			'OSC' => 'Osc',
			'DisbytegeCurrentLimit' => '放电电流限',
			'bytegeCurrentLimit' => '充电电流限',
			'TemperatureHighLimit' => '温度上限',
			'TemperatureLowLimit' => '温度下限',
			'HumiH' => '湿度上限',
			'HumiL' => '湿度下限',
			'TemperatureAdjust' => '温度偏移修正',
			'HumiAdjust' => '湿度偏移修正',
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
		$criteria->compare('HaveCurrentSensor',$this->HaveCurrentSensor);
		$criteria->compare('StationCurrentSensorSpan',$this->StationCurrentSensorSpan);
		$criteria->compare('StationCurrentSensorZeroADCode',$this->StationCurrentSensorZeroADCode);
		$criteria->compare('OSC',$this->OSC);
		$criteria->compare('DisbytegeCurrentLimit',$this->DisbytegeCurrentLimit);
		$criteria->compare('bytegeCurrentLimit',$this->bytegeCurrentLimit);
		$criteria->compare('TemperatureHighLimit',$this->TemperatureHighLimit);
		$criteria->compare('TemperatureLowLimit',$this->TemperatureLowLimit);
		$criteria->compare('HumiH',$this->HumiH);
		$criteria->compare('HumiL',$this->HumiL);
		$criteria->compare('TemperatureAdjust',$this->TemperatureAdjust);
		$criteria->compare('HumiAdjust',$this->HumiAdjust);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}