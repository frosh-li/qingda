<?php

/**
 * This is the model class for table "{{station_basic}}".
 *
 * The followings are the available columns in table '{{station_basic}}':
 * @property integer $id
 * @property string $hardware_id
 * @property string $StationSimpleChineseName
 * @property string $StationFullChineseName
 * @property string $Station_Address
 * @property string $Post_Code
 * @property string $Manager_name
 * @property string $Manager_cellphone
 * @property string $Manager_cable_phone
 * @property string $Emergency_contact_people
 * @property string $Emergency_cellphone
 * @property string $Groups_of_battery
 * @property string $Number_of_batteries_per_group
 * @property string $Design_life_time
 * @property string $User_company_name
 * @property string $Computer_OS
 * @property string $Computer_configuration
 * @property string $Supervisory_Center_manage_department
 * @property string $Supervisory_Center_manager_name
 * @property string $Supervisory_Center_manager_cellphone
 * @property string $Supervisory_Center_manager_cablephone
 * @property string $Station_Controller_Type
 * @property string $Station_Controller_Quantity
 * @property string $Group_Current_Sampler_Type
 * @property string $Group_Current_Sampler_Quantity
 * @property string $Current_Sensor_Install_Mode
 * @property string $Battery_Data_Sampler_Type
 * @property string $Battery_Data_Sampler_Quantity
 * @property string $Supervisory_Software_Version
 * @property string $Memo
 */
class StationBasic extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return StationBasic the static model class
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
		return '{{station_basic}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hardware_id, StationSimpleChineseName, StationFullChineseName, Station_Address, Post_Code, Manager_name, Manager_cellphone, Manager_cable_phone, Emergency_contact_people, Emergency_cellphone, Groups_of_battery, Number_of_batteries_per_group, Design_life_time, User_company_name, Computer_OS, Computer_configuration, Supervisory_Center_manage_department, Supervisory_Center_manager_name, Supervisory_Center_manager_cellphone, Supervisory_Center_manager_cablephone, Station_Controller_Type, Station_Controller_Quantity, Group_Current_Sampler_Type, Group_Current_Sampler_Quantity, Current_Sensor_Install_Mode, Battery_Data_Sampler_Type, Battery_Data_Sampler_Quantity, Supervisory_Software_Version, Memo', 'required'),
			array('hardware_id', 'length', 'max'=>18),
			array('StationSimpleChineseName, StationFullChineseName, Station_Address', 'length', 'max'=>300),
			array('Post_Code, Manager_name, Manager_cellphone, Manager_cable_phone, Emergency_contact_people, Emergency_cellphone, Groups_of_battery, Number_of_batteries_per_group, Design_life_time, User_company_name, Computer_OS, Computer_configuration, Supervisory_Center_manage_department, Supervisory_Center_manager_name, Supervisory_Center_manager_cellphone, Supervisory_Center_manager_cablephone, Station_Controller_Type, Station_Controller_Quantity, Group_Current_Sampler_Type, Group_Current_Sampler_Quantity, Current_Sensor_Install_Mode, Battery_Data_Sampler_Type, Battery_Data_Sampler_Quantity, Supervisory_Software_Version, Memo', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hardware_id, StationSimpleChineseName, StationFullChineseName, Station_Address, Post_Code, Manager_name, Manager_cellphone, Manager_cable_phone, Emergency_contact_people, Emergency_cellphone, Groups_of_battery, Number_of_batteries_per_group, Design_life_time, User_company_name, Computer_OS, Computer_configuration, Supervisory_Center_manage_department, Supervisory_Center_manager_name, Supervisory_Center_manager_cellphone, Supervisory_Center_manager_cablephone, Station_Controller_Type, Station_Controller_Quantity, Group_Current_Sampler_Type, Group_Current_Sampler_Quantity, Current_Sensor_Install_Mode, Battery_Data_Sampler_Type, Battery_Data_Sampler_Quantity, Supervisory_Software_Version, Memo', 'safe', 'on'=>'search'),
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
			'hardware_id' => 'Hardware',
			'StationSimpleChineseName' => 'Station Simple Chinese Name',
			'StationFullChineseName' => 'Station Full Chinese Name',
			'Station_Address' => 'Station Address',
			'Post_Code' => 'Post Code',
			'Manager_name' => 'Manager Name',
			'Manager_cellphone' => 'Manager Cellphone',
			'Manager_cable_phone' => 'Manager Cable Phone',
			'Emergency_contact_people' => 'Emergency Contact People',
			'Emergency_cellphone' => 'Emergency Cellphone',
			'Groups_of_battery' => 'Groups Of Battery',
			'Number_of_batteries_per_group' => 'Number Of Batteries Per Group',
			'Design_life_time' => 'Design Life Time',
			'User_company_name' => 'User Company Name',
			'Computer_OS' => 'Computer Os',
			'Computer_configuration' => 'Computer Configuration',
			'Supervisory_Center_manage_department' => 'Supervisory Center Manage Department',
			'Supervisory_Center_manager_name' => 'Supervisory Center Manager Name',
			'Supervisory_Center_manager_cellphone' => 'Supervisory Center Manager Cellphone',
			'Supervisory_Center_manager_cablephone' => 'Supervisory Center Manager Cablephone',
			'Station_Controller_Type' => 'Station Controller Type',
			'Station_Controller_Quantity' => 'Station Controller Quantity',
			'Group_Current_Sampler_Type' => 'Group Current Sampler Type',
			'Group_Current_Sampler_Quantity' => 'Group Current Sampler Quantity',
			'Current_Sensor_Install_Mode' => 'Current Sensor Install Mode',
			'Battery_Data_Sampler_Type' => 'Battery Data Sampler Type',
			'Battery_Data_Sampler_Quantity' => 'Battery Data Sampler Quantity',
			'Supervisory_Software_Version' => 'Supervisory Software Version',
			'Memo' => 'Memo',
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
		$criteria->compare('hardware_id',$this->hardware_id,true);
		$criteria->compare('StationSimpleChineseName',$this->StationSimpleChineseName,true);
		$criteria->compare('StationFullChineseName',$this->StationFullChineseName,true);
		$criteria->compare('Station_Address',$this->Station_Address,true);
		$criteria->compare('Post_Code',$this->Post_Code,true);
		$criteria->compare('Manager_name',$this->Manager_name,true);
		$criteria->compare('Manager_cellphone',$this->Manager_cellphone,true);
		$criteria->compare('Manager_cable_phone',$this->Manager_cable_phone,true);
		$criteria->compare('Emergency_contact_people',$this->Emergency_contact_people,true);
		$criteria->compare('Emergency_cellphone',$this->Emergency_cellphone,true);
		$criteria->compare('Groups_of_battery',$this->Groups_of_battery,true);
		$criteria->compare('Number_of_batteries_per_group',$this->Number_of_batteries_per_group,true);
		$criteria->compare('Design_life_time',$this->Design_life_time,true);
		$criteria->compare('User_company_name',$this->User_company_name,true);
		$criteria->compare('Computer_OS',$this->Computer_OS,true);
		$criteria->compare('Computer_configuration',$this->Computer_configuration,true);
		$criteria->compare('Supervisory_Center_manage_department',$this->Supervisory_Center_manage_department,true);
		$criteria->compare('Supervisory_Center_manager_name',$this->Supervisory_Center_manager_name,true);
		$criteria->compare('Supervisory_Center_manager_cellphone',$this->Supervisory_Center_manager_cellphone,true);
		$criteria->compare('Supervisory_Center_manager_cablephone',$this->Supervisory_Center_manager_cablephone,true);
		$criteria->compare('Station_Controller_Type',$this->Station_Controller_Type,true);
		$criteria->compare('Station_Controller_Quantity',$this->Station_Controller_Quantity,true);
		$criteria->compare('Group_Current_Sampler_Type',$this->Group_Current_Sampler_Type,true);
		$criteria->compare('Group_Current_Sampler_Quantity',$this->Group_Current_Sampler_Quantity,true);
		$criteria->compare('Current_Sensor_Install_Mode',$this->Current_Sensor_Install_Mode,true);
		$criteria->compare('Battery_Data_Sampler_Type',$this->Battery_Data_Sampler_Type,true);
		$criteria->compare('Battery_Data_Sampler_Quantity',$this->Battery_Data_Sampler_Quantity,true);
		$criteria->compare('Supervisory_Software_Version',$this->Supervisory_Software_Version,true);
		$criteria->compare('Memo',$this->Memo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}