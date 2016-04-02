<?php

/**
 * This is the model class for table "{{program_gerneral_parameters}}".
 *
 * The followings are the available columns in table '{{program_gerneral_parameters}}':
 * @property integer $id
 * @property string $password
 * @property double $time_for_db_write_interval
 * @property integer $b_station_basic_infor_changed
 * @property double $N_histroy_average_number
 * @property double $station_delta_I_write_history_db
 * @property double $battery_delta_U_write_history_db
 * @property double $battery_delta_R_write_history_db
 * @property double $battery_delta_T_write_history_db
 * @property double $group_delta_I_write_history_db
 * @property double $group_delta_U_write_history_db
 * @property double $group_delta_T_write_history_db
 * @property string $memo
 */
class GerneralParameters extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GerneralParameters the static model class
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
		return '{{program_gerneral_parameters}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('b_station_basic_infor_changed', 'numerical', 'integerOnly'=>true),
			array('time_for_db_write_interval, N_histroy_average_number, station_delta_I_write_history_db, battery_delta_U_write_history_db, battery_delta_R_write_history_db, battery_delta_T_write_history_db, group_delta_I_write_history_db, group_delta_U_write_history_db, group_delta_T_write_history_db', 'numerical'),
			array('password, memo', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, password, time_for_db_write_interval, b_station_basic_infor_changed, N_histroy_average_number, station_delta_I_write_history_db, battery_delta_U_write_history_db, battery_delta_R_write_history_db, battery_delta_T_write_history_db, group_delta_I_write_history_db, group_delta_U_write_history_db, group_delta_T_write_history_db, memo', 'safe', 'on'=>'search'),
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
			'password' => 'Password',
			'time_for_db_write_interval' => 'Time For Db Write Interval',
			'b_station_basic_infor_changed' => 'B Station Basic Infor Changed',
			'N_histroy_average_number' => 'N Histroy Average Number',
			'station_delta_I_write_history_db' => 'Station Delta I Write History Db',
			'battery_delta_U_write_history_db' => 'Battery Delta U Write History Db',
			'battery_delta_R_write_history_db' => 'Battery Delta R Write History Db',
			'battery_delta_T_write_history_db' => 'Battery Delta T Write History Db',
			'group_delta_I_write_history_db' => 'Group Delta I Write History Db',
			'group_delta_U_write_history_db' => 'Group Delta U Write History Db',
			'group_delta_T_write_history_db' => 'Group Delta T Write History Db',
			'memo' => 'Memo',
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
		$criteria->compare('password',$this->password,true);
		$criteria->compare('time_for_db_write_interval',$this->time_for_db_write_interval);
		$criteria->compare('b_station_basic_infor_changed',$this->b_station_basic_infor_changed);
		$criteria->compare('N_histroy_average_number',$this->N_histroy_average_number);
		$criteria->compare('station_delta_I_write_history_db',$this->station_delta_I_write_history_db);
		$criteria->compare('battery_delta_U_write_history_db',$this->battery_delta_U_write_history_db);
		$criteria->compare('battery_delta_R_write_history_db',$this->battery_delta_R_write_history_db);
		$criteria->compare('battery_delta_T_write_history_db',$this->battery_delta_T_write_history_db);
		$criteria->compare('group_delta_I_write_history_db',$this->group_delta_I_write_history_db);
		$criteria->compare('group_delta_U_write_history_db',$this->group_delta_U_write_history_db);
		$criteria->compare('group_delta_T_write_history_db',$this->group_delta_T_write_history_db);
		$criteria->compare('memo',$this->memo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}