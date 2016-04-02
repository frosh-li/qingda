<?php

/**
 * This is the model class for table "{{admintype}}".
 *
 * The followings are the available columns in table '{{admintype}}':
 * @property integer $id
 * @property integer $role
 * @property string $typename
 * @property integer $system
 * @property string $create_time
 * @property string $update_time
 */
class Admintype extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Admintype the static model class
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
		return '{{admintype}}';
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
        $this->system = 1;
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
			array('create_time, update_time', 'required'),
			array('role, system', 'numerical', 'integerOnly'=>true),
			array('typename', 'length', 'max'=>30),
			array('create_time, update_time', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, role, typename, system, create_time, update_time', 'safe', 'on'=>'search'),
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
			'role' => '类型ID',
			'typename' => '类型名称',
			'system' => 'System',
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
		$criteria->compare('role',$this->role);
		$criteria->compare('typename',$this->typename,true);
		$criteria->compare('system',$this->system);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}