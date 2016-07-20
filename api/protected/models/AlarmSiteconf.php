<?php

/**
 * This is the model class for table "{{alarm_siteconf}}".
 *
 * The followings are the available columns in table '{{alarm_siteconf}}':
 * @property integer $id
 * @property integer $category
 * @property integer $type
 * @property string $content
 * @property string $suggest
 * @property string $send_email
 * @property integer $send_msg
 * @property integer $alarm_type
 * @property string $type_value
 * @property string $operator
 * @property integer $judge_type
 * @property integer $can_ignore
 * @property string $alarm
 * @property string $alarm_code
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $sn_key
 * @property integer $status
 */
class AlarmSiteconf extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AlarmSiteconf the static model class
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
		return '{{alarm_siteconf}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sn_key, category, type, content, suggest, send_email, send_msg, can_ignore, create_time, update_time', 'required'),
			array('sn_key, category, type, send_msg, alarm_type, judge_type, can_ignore,status, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>100),
			array('suggest', 'length', 'max'=>255),
			array('type_value', 'length', 'max'=>50),
			array('operator', 'length', 'max'=>10),
			array('alarm_code', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sn_key, category, type, content, suggest, send_email, send_msg, alarm_type, type_value, operator, judge_type, can_ignore, alarm_code, create_time, update_time', 'safe', 'on'=>'search'),
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
			'category' => 'Category',
			'type' => 'Type',
			'content' => 'Content',
			'suggest' => 'Suggest',
			'send_email' => 'Send Email',
			'send_msg' => 'Send Msg',
			'alarm_type' => 'Alarm Type',
			'type_value' => 'Type Value',
			'operator' => 'Operator',
			'judge_type' => 'Judge Type',
			'can_ignore' => 'Can Ignore',
			'alarm_code' => 'Alarm Code',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'sn_key' => 'Sn Key',
			'status' => 'Status'
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
		$criteria->compare('category',$this->category);
		$criteria->compare('type',$this->type);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('suggest',$this->suggest,true);
		$criteria->compare('send_email',$this->send_email,true);
		$criteria->compare('send_msg',$this->send_msg);
		$criteria->compare('alarm_type',$this->alarm_type);
		$criteria->compare('type_value',$this->type_value,true);
		$criteria->compare('operator',$this->operator,true);
		$criteria->compare('judge_type',$this->judge_type);
		$criteria->compare('can_ignore',$this->can_ignore);
		$criteria->compare('alarm_code',$this->alarm_code,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('sn_key',$this->sn_key);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}