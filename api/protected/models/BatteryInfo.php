<?php

/**
 * This is the model class for table "{{battery_info}}".
 *
 * The followings are the available columns in table '{{battery_info}}':
 * @property integer $id
 * @property integer $sid
 * @property string $battery_factory
 * @property string $battery_num
 * @property string $battery_date
 * @property double $battery_voltage
 * @property double $battery_oum
 * @property double $battery_max_current
 * @property double $battery_float_up
 * @property double $battery_float_dow
 * @property double $battery_discharge_down
 * @property string $battery_scrap_date
 * @property double $battery_life
 * @property string $battery_column_type
 * @property double $battery_humidity
 * @property string $battery_type
 * @property string $battery_factory_phone
 * @property integer $create_time
 * @property integer $update_time
 */
class BatteryInfo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BatteryInfo the static model class
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
		return '{{battery_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sid, battery_factory, create_time, update_time', 'required'),
			array('sid, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('battery_voltage, battery_oum, battery_max_current, battery_float_up, battery_float_dow, battery_discharge_down, battery_life, battery_humidity,battery_temperature', 'numerical'),
			array('battery_factory', 'length', 'max'=>100),
			array('battery_num, battery_type', 'length', 'max'=>50),
			array('battery_column_type, battery_factory_phone', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sid, battery_factory, battery_num, battery_date, battery_voltage, battery_oum, battery_max_current, battery_float_up, battery_float_dow, battery_discharge_down, battery_scrap_date, battery_life, battery_column_type, battery_humidity, battery_type, battery_factory_phone, create_time, update_time', 'safe', 'on'=>'search'),
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
			'battery_factory' => '电池生产厂家',
			'battery_num' => 'Battery Num',
			'battery_date' => 'Battery Date',
			'battery_voltage' => 'Battery Voltage',
			'battery_oum' => 'Battery Oum',
			'battery_max_current' => 'Battery Max Current',
			'battery_float_up' => 'Battery Float Up',
			'battery_float_dow' => 'Battery Float Dow',
			'battery_discharge_down' => 'Battery Discharge Down',
			'battery_scrap_date' => 'Battery Scrap Date',
			'battery_life' => 'Battery Life',
			'battery_column_type' => 'Battery Column Type',
			'battery_humidity' => 'Battery Humidity',
			'battery_type' => 'Battery Type',
			'battery_factory_phone' => 'Battery Factory Phone',
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
		$criteria->compare('battery_factory',$this->battery_factory,true);
		$criteria->compare('battery_num',$this->battery_num,true);
		$criteria->compare('battery_date',$this->battery_date,true);
		$criteria->compare('battery_voltage',$this->battery_voltage);
		$criteria->compare('battery_oum',$this->battery_oum);
		$criteria->compare('battery_max_current',$this->battery_max_current);
		$criteria->compare('battery_float_up',$this->battery_float_up);
		$criteria->compare('battery_float_dow',$this->battery_float_dow);
		$criteria->compare('battery_discharge_down',$this->battery_discharge_down);
		$criteria->compare('battery_scrap_date',$this->battery_scrap_date,true);
		$criteria->compare('battery_life',$this->battery_life);
		$criteria->compare('battery_column_type',$this->battery_column_type,true);
		$criteria->compare('battery_humidity',$this->battery_humidity);
		$criteria->compare('battery_type',$this->battery_type,true);
		$criteria->compare('battery_factory_phone',$this->battery_factory_phone,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}