<?php

/**
 * This is the model class for table "{{battery_parameter}}".
 *
 * The followings are the available columns in table '{{battery_parameter}}':
 * @property string $id
 * @property double $CurrentAdjust_KV
 * @property double $TemperatureAdjust_KT
 * @property integer $T0_ADC
 * @property integer $T0_Temperature
 * @property integer $T1_ADC
 * @property integer $T1_Temperature
 * @property integer $Rin_Span
 * @property integer $OSC
 * @property integer $BatteryU_H
 * @property integer $BaterryU_L
 * @property integer $Electrode_T_H_Limit
 * @property integer $Electrode_T_L_Limit
 * @property integer $Rin_High_Limit
 * @property double $Rin_Adjust_KR
 * @property double $PreAmp_KA
 * @property double $Rin_ExciteI_KI
 */
class BatteryParameter extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BatteryParameter the static model class
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
		return '{{battery_parameter}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('T0_ADC, T0_Temperature, T1_ADC, T1_Temperature, Rin_Span, OSC,  Rin_High_Limit', 'numerical', 'integerOnly'=>true),
			array('CurrentAdjust_KV, TemperatureAdjust_KT, Rin_Adjust_KR,Electrode_T_H_Limit,Electrode_T_L_Limit, PreAmp_KA, BatteryU_H, BaterryU_L,Rin_ExciteI_KI', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, CurrentAdjust_KV, TemperatureAdjust_KT, T0_ADC, T0_Temperature, T1_ADC, T1_Temperature, Rin_Span, OSC, BatteryU_H, BaterryU_L, Electrode_T_H_Limit, Electrode_T_L_Limit, Rin_High_Limit, Rin_Adjust_KR, PreAmp_KA, Rin_ExciteI_KI', 'safe', 'on'=>'search'),
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
			'CurrentAdjust_KV' => '电压测量修正系数',
			'TemperatureAdjust_KT' => '温度测量修正系数',
			'T0_ADC' => 'T0校准点ADC码',
			'T0_Temperature' => 'T0校准点温度值',
			'T1_ADC' => 'T1校准点ADC码',
			'T1_Temperature' => 'T1校准点温度值',
			'Rin_Span' => '内阻测量量程',
			'OSC' => 'OSC',
			'BatteryU_H' => '电池电压高压限',
			'BaterryU_L' => '电池电压低压限',
			'Electrode_T_H_Limit' => '电极温度高温限',
			'Electrode_T_L_Limit' => '电极温度低温限',
			'Rin_High_Limit' => '电池内阻高限',
			'Rin_Adjust_KR' => '内阻测量修正系数',
			'PreAmp_KA' => '前置放大器修正系数',
			'Rin_ExciteI_KI' => '内阻激励电流修正系数',
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
		$criteria->compare('CurrentAdjust_KV',$this->CurrentAdjust_KV);
		$criteria->compare('TemperatureAdjust_KT',$this->TemperatureAdjust_KT);
		$criteria->compare('T0_ADC',$this->T0_ADC);
		$criteria->compare('T0_Temperature',$this->T0_Temperature);
		$criteria->compare('T1_ADC',$this->T1_ADC);
		$criteria->compare('T1_Temperature',$this->T1_Temperature);
		$criteria->compare('Rin_Span',$this->Rin_Span);
		$criteria->compare('OSC',$this->OSC);
		$criteria->compare('BatteryU_H',$this->BatteryU_H);
		$criteria->compare('BaterryU_L',$this->BaterryU_L);
		$criteria->compare('Electrode_T_H_Limit',$this->Electrode_T_H_Limit);
		$criteria->compare('Electrode_T_L_Limit',$this->Electrode_T_L_Limit);
		$criteria->compare('Rin_High_Limit',$this->Rin_High_Limit);
		$criteria->compare('Rin_Adjust_KR',$this->Rin_Adjust_KR);
		$criteria->compare('PreAmp_KA',$this->PreAmp_KA);
		$criteria->compare('Rin_ExciteI_KI',$this->Rin_ExciteI_KI);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}