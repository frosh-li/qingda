<?php

/**
 * This is the model class for table "{{station_parameter}}".
 *
 * The followings are the available columns in table '{{station_parameter}}':
 * @property string $id
 * @property integer $Time_interval_Rin
 * @property integer $Time_interval_U
 * @property integer $U_abnormal_limit
 * @property integer $T_abnormal_limit
 * @property integer $Rin_abnormal_limit
 * @property integer $T_upper_limit
 * @property integer $T_lower_limit
 * @property integer $Humi_upper_limit
 * @property integer $Humi_lower_limit
 * @property integer $Group_I_criterion
 * @property integer $bytegeStatus_U_upper
 * @property integer $bytegeStatus_U_lower
 * @property integer $FloatingbytegeStatus_U_upper
 * @property integer $FloatingbytegeStatus_U_lower
 * @property integer $DisbytegeStatus_U_upper
 * @property integer $DisbytegeStatus_U_lower
 * @property integer $N_Groups_Incide_Station
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
class StationParameter extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StationParameter the static model class
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
		return '{{station_parameter}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Time_interval_Rin, Time_interval_U, T_abnormal_limit, Rin_abnormal_limit, T_lower_limit, Humi_upper_limit, Humi_lower_limit, bytegeStatus_U_lower, FloatingbytegeStatus_U_lower, DisbytegeStatus_U_lower, N_Groups_Incide_Station, HaveCurrentSensor, StationCurrentSensorSpan, StationCurrentSensorZeroADCode, OSC, DisbytegeCurrentLimit, bytegeCurrentLimit, TemperatureHighLimit, TemperatureLowLimit, HumiH, HumiL, TemperatureAdjust, HumiAdjust', 'numerical', 'integerOnly'=>true),
            array('U_abnormal_limit,T_upper_limit,Group_I_criterion,bytegeStatus_U_upper,FloatingbytegeStatus_U_upper,DisbytegeStatus_U_upper', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, Time_interval_Rin, Time_interval_U, U_abnormal_limit, T_abnormal_limit, Rin_abnormal_limit, T_upper_limit, T_lower_limit, Humi_upper_limit, Humi_lower_limit, Group_I_criterion, bytegeStatus_U_upper, bytegeStatus_U_lower, DisbytegeStatus_U_upper, FloatingbytegeStatus_U_lower, DisbytegeStatus_U_upper, DisbytegeStatus_U_lower, N_Groups_Incide_Station, HaveCurrentSensor, StationCurrentSensorSpan, StationCurrentSensorZeroADCode, OSC, DisbytegeCurrentLimit, bytegeCurrentLimit, TemperatureHighLimit, TemperatureLowLimit, HumiH, HumiL, TemperatureAdjust, HumiAdjust', 'safe', 'on'=>'search'),
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
            'Time_interval_Rin' => '内阻测量间隔（S）',
            'Time_interval_U' => '电压测量间隔（S）',
            'U_abnormal_limit' => '站电压异常限（V）',
            'T_abnormal_limit' => '站温度异常限（℃）',
            'Rin_abnormal_limit' => '站内阻异常限（mΩ）',
            'T_upper_limit' => '站温度上限（℃）',
            'T_lower_limit' => '站温度下限（℃）',
            'Humi_upper_limit' => '站湿度上限（%）',
            'Humi_lower_limit' => '站湿度下限（%）',
            'Group_I_criterion' => '站电流状态判据（A）',
            'bytegeStatus_U_upper' => '充电态高压限（V）',
            'bytegeStatus_U_lower' => '充电态低压限（V）',
            'FloatingbytegeStatus_U_upper' => '浮充态高压限（V）',
            'FloatingbytegeStatus_U_lower' => '浮充态低压限（V）',
            'DisbytegeStatus_U_upper' => '放电态高压限（V）',
            'DisbytegeStatus_U_lower' => '放电态低压限（V）',
            'HaveCurrentSensor' => '有无电流传感器',
            'StationCurrentSensorSpan' => '电流传感器量程（A）',
            'StationCurrentSensorZeroADCode' => '电流传感器零位AD码',
            'OSC' => 'OSC',
            'DisbytegeCurrentLimit' => '放电电流限（A）',
            'bytegeCurrentLimit' => '充电电流限（A）',
            'TemperatureHighLimit' => '温度上限（℃）',
            'TemperatureLowLimit' => '温度下限（℃）',
            'HumiH' => '湿度上限（%）',
            'HumiL' => '湿度下限（%）',
            'TemperatureAdjust' => '温度偏移修正（℃）',
            'HumiAdjust' => '湿度偏移修正（%）',
            'N_Groups_Incide_Station' => '本站组数',
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
		$criteria->compare('Time_interval_Rin',$this->Time_interval_Rin);
		$criteria->compare('Time_interval_U',$this->Time_interval_U);
		$criteria->compare('U_abnormal_limit',$this->U_abnormal_limit);
		$criteria->compare('T_abnormal_limit',$this->T_abnormal_limit);
		$criteria->compare('Rin_abnormal_limit',$this->Rin_abnormal_limit);
		$criteria->compare('T_upper_limit',$this->T_upper_limit);
		$criteria->compare('T_lower_limit',$this->T_lower_limit);
		$criteria->compare('Humi_upper_limit',$this->Humi_upper_limit);
		$criteria->compare('Humi_lower_limit',$this->Humi_lower_limit);
		$criteria->compare('Group_I_criterion',$this->Group_I_criterion);
		$criteria->compare('bytegeStatus_U_upper',$this->bytegeStatus_U_upper);
		$criteria->compare('bytegeStatus_U_lower',$this->bytegeStatus_U_lower);
		$criteria->compare('FloatingbytegeStatus_U_upper',$this->FloatingbytegeStatus_U_upper);
		$criteria->compare('FloatingbytegeStatus_U_lower',$this->FloatingbytegeStatus_U_lower);
		$criteria->compare('DisbytegeStatus_U_upper',$this->DisbytegeStatus_U_upper);
		$criteria->compare('DisbytegeStatus_U_lower',$this->DisbytegeStatus_U_lower);
		$criteria->compare('N_Groups_Incide_Station',$this->N_Groups_Incide_Station);
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