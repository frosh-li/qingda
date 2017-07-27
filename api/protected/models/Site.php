<?php

/**
 * This is the model class for table "{{site}}".
 *
 * The followings are the available columns in table '{{site}}':
 * @property string $id
 * @property string $sid
 * @property string $site_name
 * @property string $StationFullChineseName
 * @property string $serial_number
 * @property string $site_property
 * @property string $site_location
 * @property string $site_chname
 * @property double $site_longitude
 * @property double $site_latitude
 * @property string $ipaddress
 * @property string $ipaddress_method
 * @property string $site_control_type
 * @property string $postal_code
 * @property string $emergency_phone
 * @property string $emergency_person
 * @property string $remark
 * @property integer $aid
 * @property integer $groups
 * @property integer $batteries
 * @property string $battery_status
 * @property string $bms_install_date
 * @property string $group_collect_type
 * @property integer $group_collect_num
 * @property string $inductor_brand
 * @property string $inductor_type
 * @property string $group_collect_install_type
 * @property string $battery_collect_type
 * @property integer $battery_collect_num
 * @property string $humiture_type
 * @property string $create_time
 * @property string $update_time
 */
class Site extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Site the static model class
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
		return '{{site}}';
	}
    /**
     * Prepares posttime  attributes before performing validation.
     */
    protected function beforeValidate() {

        if ($this->isNewRecord) {
            $this->create_time = $this->update_time = date('Y-m-d H:i:s');
        }else{
            $this->update_time = date('Y-m-d H:i:s');
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
			//array('sid, site_name, StationFullChineseName, serial_number, site_location, aid, groups, batteries, battery_status, bms_install_date, group_collect_type, group_collect_num, inductor_brand, inductor_type, group_collect_install_type, battery_collect_type, create_time, update_time', 'required'),
			array('CurSensor,site_name, StationFullChineseName, serial_number, create_time, update_time', 'required'),
			array('aid, groups, batteries, group_collect_num, battery_collect_num,is_checked', 'numerical', 'integerOnly'=>true),
			array('site_longitude, site_latitude', 'numerical'),
			array('sid', 'length', 'max'=>11),
			array('site_name, site_location, site_chname', 'length', 'max'=>255),
			array('StationFullChineseName', 'length', 'max'=>300),
			array('serial_number', 'length', 'max'=>100),
			array('site_property, inductor_brand', 'length', 'max'=>50),
			array('ipaddress, postal_code, emergency_phone', 'length', 'max'=>20),
			array('ipaddress_method, site_control_type, emergency_person, battery_status, group_collect_type, inductor_type, group_collect_install_type, battery_collect_type, humiture_type', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sid,CurSensor, site_name, StationFullChineseName, serial_number, site_property, site_location, site_chname, site_longitude, site_latitude, ipaddress, ipaddress_method, site_control_type, postal_code, emergency_phone, emergency_person, remark, aid, groups, batteries, battery_status, bms_install_date, group_collect_type, group_collect_num, inductor_brand, inductor_type, group_collect_install_type, battery_collect_type, battery_collect_num, humiture_type, create_time, update_time', 'safe', 'on'=>'search'),
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
			'CurSensor' => 'CurSensor',
			'site_name' => 'Site Name',
			'StationFullChineseName' => 'Station Full Chinese Name',
			'serial_number' => 'Serial Number',
			'site_property' => 'Site Property',
			'site_location' => 'Site Location',
			'site_chname' => 'Site Chname',
			'site_longitude' => 'Site Longitude',
			'site_latitude' => 'Site Latitude',
			'ipaddress' => 'Ipaddress',
			'ipaddress_method' => 'Ipaddress Method',
			'site_control_type' => 'Site Control Type',
			'postal_code' => 'Postal Code',
			'emergency_phone' => 'Emergency Phone',
			'emergency_person' => 'Emergency Person',
			'remark' => 'Remark',
			'aid' => 'Aid',
			'groups' => 'Groups',
			'batteries' => 'Batteries',
			'battery_status' => 'Battery Status',
			'bms_install_date' => 'Bms Install Date',
			'group_collect_type' => 'Group Collect Type',
			'group_collect_num' => 'Group Collect Num',
			'inductor_brand' => 'Inductor Brand',
			'inductor_type' => 'Inductor Type',
			'group_collect_install_type' => 'Group Collect Install Type',
			'battery_collect_type' => 'Battery Collect Type',
			'battery_collect_num' => 'Battery Collect Num',
			'humiture_type' => 'Humiture Type',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'functionary_sms' => 'functionary_sms'
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
		$criteria->compare('sid',$this->sid,true);
		$criteria->compare('CurSensor',$this->CurSensor,true);
		
		$criteria->compare('site_name',$this->site_name,true);
		$criteria->compare('StationFullChineseName',$this->StationFullChineseName,true);
		$criteria->compare('serial_number',$this->serial_number,true);
		$criteria->compare('site_property',$this->site_property,true);
		$criteria->compare('site_location',$this->site_location,true);
		$criteria->compare('site_chname',$this->site_chname,true);
		$criteria->compare('site_longitude',$this->site_longitude);
		$criteria->compare('site_latitude',$this->site_latitude);
		$criteria->compare('ipaddress',$this->ipaddress,true);
		$criteria->compare('ipaddress_method',$this->ipaddress_method,true);
		$criteria->compare('site_control_type',$this->site_control_type,true);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('emergency_phone',$this->emergency_phone,true);
		$criteria->compare('emergency_person',$this->emergency_person,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('aid',$this->aid);
		$criteria->compare('groups',$this->groups);
		$criteria->compare('batteries',$this->batteries);
		$criteria->compare('battery_status',$this->battery_status,true);
		$criteria->compare('bms_install_date',$this->bms_install_date,true);
		$criteria->compare('group_collect_type',$this->group_collect_type,true);
		$criteria->compare('group_collect_num',$this->group_collect_num);
		$criteria->compare('inductor_brand',$this->inductor_brand,true);
		$criteria->compare('inductor_type',$this->inductor_type,true);
		$criteria->compare('group_collect_install_type',$this->group_collect_install_type,true);
		$criteria->compare('battery_collect_type',$this->battery_collect_type,true);
		$criteria->compare('battery_collect_num',$this->battery_collect_num);
		$criteria->compare('humiture_type',$this->humiture_type,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}