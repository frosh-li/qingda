<?php

/**
 * This is the model class for table "{{company_info}}".
 *
 * The followings are the available columns in table '{{company_info}}':
 * @property integer $id
 * @property string $company_name
 * @property string $company_address
 * @property string $supervisor_phone
 * @property string $supervisor_name
 * @property double $longitude
 * @property double $latitude
 * @property integer $station_num
 * @property integer $area_level
 * @property string $network_type
 * @property integer $bandwidth
 * @property string $ipaddr
 * @property string $computer_brand
 * @property string $computer_os
 * @property string $computer_conf
 * @property string $browser_name
 * @property string $server_capacity
 * @property string $server_type
 * @property string $cloud_address
 * @property string $backup_period
 * @property string $backup_type
 * @property string $supervisor_depname
 * @property string $monitor_name1
 * @property string $monitor_phone1
 * @property string $monitor_name2
 * @property string $monitor_phone2
 * @property string $monitor_name3
 * @property string $monitor_phone3
 * @property string $monitor_tel1
 * @property string $monitor_tel2
 * @property string $modify_time
 */
class CompanyInfo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CompanyInfo the static model class
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
		return '{{company_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_name, company_address, supervisor_phone, supervisor_name, longitude, latitude, station_num, area_level, network_type, bandwidth, ipaddr, computer_brand, computer_os, computer_conf, browser_name, server_capacity, server_type, cloud_address, backup_period, backup_type, supervisor_depname, monitor_name1, monitor_phone1, monitor_name2, monitor_phone2, monitor_name3, monitor_phone3, monitor_tel1, monitor_tel2, modify_time', 'required'),
			array('station_num, area_level, bandwidth', 'numerical', 'integerOnly'=>true),
			array('longitude, latitude', 'numerical'),
			array('company_name', 'length', 'max'=>200),
			array('company_address, network_type, supervisor_depname', 'length', 'max'=>255),
			array('supervisor_phone, ipaddr, backup_period, monitor_phone1, monitor_phone2, monitor_tel1, monitor_tel2', 'length', 'max'=>20),
			array('supervisor_name, computer_brand, computer_os', 'length', 'max'=>100),
			array('computer_conf, cloud_address', 'length', 'max'=>150),
			array('browser_name, server_capacity, server_type, backup_type, monitor_name1, monitor_name2, monitor_name3, monitor_phone3', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, company_name, company_address, supervisor_phone, supervisor_name, longitude, latitude, station_num, area_level, network_type, bandwidth, ipaddr, computer_brand, computer_os, computer_conf, browser_name, server_capacity, server_type, cloud_address, backup_period, backup_type, supervisor_depname, monitor_name1, monitor_phone1, monitor_name2, monitor_phone2, monitor_name3, monitor_phone3, monitor_tel1, monitor_tel2, modify_time', 'safe', 'on'=>'search'),
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
			'company_name' => 'Company Name',
			'company_address' => 'Company Address',
			'supervisor_phone' => 'Supervisor Phone',
			'supervisor_name' => 'Supervisor Name',
			'longitude' => 'Longitude',
			'latitude' => 'Latitude',
			'station_num' => 'Station Num',
			'area_level' => 'Area Level',
			'network_type' => 'Network Type',
			'bandwidth' => 'Bandwidth',
			'ipaddr' => 'Ipaddr',
			'computer_brand' => 'Computer Brand',
			'computer_os' => 'Computer Os',
			'computer_conf' => 'Computer Conf',
			'browser_name' => 'Browser Name',
			'server_capacity' => 'Server Capacity',
			'server_type' => 'Server Type',
			'cloud_address' => 'Cloud Address',
			'backup_period' => 'Backup Period',
			'backup_type' => 'Backup Type',
			'supervisor_depname' => 'Supervisor Depname',
			'monitor_name1' => 'Monitor Name1',
			'monitor_phone1' => 'Monitor Phone1',
			'monitor_name2' => 'Monitor Name2',
			'monitor_phone2' => 'Monitor Phone2',
			'monitor_name3' => 'Monitor Name3',
			'monitor_phone3' => 'Monitor Phone3',
			'monitor_tel1' => 'Monitor Tel1',
			'monitor_tel2' => 'Monitor Tel2',
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
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('company_address',$this->company_address,true);
		$criteria->compare('supervisor_phone',$this->supervisor_phone,true);
		$criteria->compare('supervisor_name',$this->supervisor_name,true);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('station_num',$this->station_num);
		$criteria->compare('area_level',$this->area_level);
		$criteria->compare('network_type',$this->network_type,true);
		$criteria->compare('bandwidth',$this->bandwidth);
		$criteria->compare('ipaddr',$this->ipaddr,true);
		$criteria->compare('computer_brand',$this->computer_brand,true);
		$criteria->compare('computer_os',$this->computer_os,true);
		$criteria->compare('computer_conf',$this->computer_conf,true);
		$criteria->compare('browser_name',$this->browser_name,true);
		$criteria->compare('server_capacity',$this->server_capacity,true);
		$criteria->compare('server_type',$this->server_type,true);
		$criteria->compare('cloud_address',$this->cloud_address,true);
		$criteria->compare('backup_period',$this->backup_period,true);
		$criteria->compare('backup_type',$this->backup_type,true);
		$criteria->compare('supervisor_depname',$this->supervisor_depname,true);
		$criteria->compare('monitor_name1',$this->monitor_name1,true);
		$criteria->compare('monitor_phone1',$this->monitor_phone1,true);
		$criteria->compare('monitor_name2',$this->monitor_name2,true);
		$criteria->compare('monitor_phone2',$this->monitor_phone2,true);
		$criteria->compare('monitor_name3',$this->monitor_name3,true);
		$criteria->compare('monitor_phone3',$this->monitor_phone3,true);
		$criteria->compare('monitor_tel1',$this->monitor_tel1,true);
		$criteria->compare('monitor_tel2',$this->monitor_tel2,true);
		$criteria->compare('modify_time',$this->modify_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}