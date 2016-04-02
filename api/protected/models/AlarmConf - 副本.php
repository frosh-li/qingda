<?php

/**
 * This is the model class for table "{{alarm_conf}}".
 *
 * The followings are the available columns in table '{{alarm_conf}}':
 * @property integer $id
 * @property integer $type
 * @property string $content
 * @property string $suggest
 * @property string $send_email
 * @property integer $send_msg
 * @property string $type_red
 * @property string $type_orange
 * @property string $type_yellow
 * @property integer $create_time
 * @property integer $update_time
 */
class AlarmConf extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AlarmConf the static model class
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
		return '{{alarm_conf}}';
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, content, suggest, send_email, send_msg, create_time, update_time', 'required'),
			array('type, send_msg, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>100),
			array('suggest', 'length', 'max'=>255),
			array('type_red, type_orange, type_yellow', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, content, suggest, send_email, send_msg, type_red, type_orange, type_yellow, create_time, update_time', 'safe', 'on'=>'search'),
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
			'type' => '报警编号',
			'content' => '警情内容',
			'suggest' => '处理建议',
			'send_email' => '是否发邮件',
			'send_msg' => '是否发短信',
			'type_red' => '红色标准',
			'type_orange' => '橙色标准',
			'type_yellow' => '黄色标准',
			'create_time' => '创建时间',
			'update_time' => '修改时间',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('suggest',$this->suggest,true);
		$criteria->compare('send_email',$this->send_email,true);
		$criteria->compare('send_msg',$this->send_msg);
		$criteria->compare('type_red',$this->type_red,true);
		$criteria->compare('type_orange',$this->type_orange,true);
		$criteria->compare('type_yellow',$this->type_yellow,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
        $criteria->order = 'id desc';
		return new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=>25,//设置每页显示20条
            ),

            'sort'=>array(
                'defaultOrder'=>'create_time DESC', //设置默认排序是create_time倒序
            ),
			'criteria'=>$criteria,
		));
	}
}