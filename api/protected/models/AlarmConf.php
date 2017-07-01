<?php

/**
 * This is the model class for table "{{alarm_conf}}".
 *
 * The followings are the available columns in table '{{alarm_conf}}':
 * @property integer $id
 * @property integer $category
 * @property integer $type
 * @property string $content
 * @property string $suggest
 * @property string $send_email
 * @property integer $send_msg
 * @property string $type_value
 * @property string $alarm_type
 * @property string $operator
 * @property integer $judge_type
 * @property integer $can_ignore
 * @property string $alarm_code
 * @property integer $create_time
 * @property integer $update_time
 */
class AlarmConf extends CActiveRecord
{
    public $categorys = array(
        1=>'站报警',
        2=>'组报警',
        3=>'电池报警',
        4=>'其他报警',
    );
    public $alarmaypes = array(
        1=>'红色',
        2=>'橙色',
        3=>'黄色',
    );
    public $operators = array(
        '>'=>'大于等于',
        '<'=>'小于等于',
        '='=>'等于',
        'o'=>'其他',
    );
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
			array('category, type, content,alarm_type, suggest, send_email, send_msg, judge_type,can_ignore, create_time, update_time', 'required'),
			array('category, type, send_msg, judge_type,can_ignore,alarm_type, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>100),
			array('suggest', 'length', 'max'=>255),
            array('operator', 'length', 'max'=>10),
			array('type_value', 'length', 'max'=>50),
			array('alarm_code', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category, type, content, suggest, send_email, send_msg, type_value, alarm_type, judge_type,can_ignore, alarm_code, create_time, update_time', 'safe', 'on'=>'search'),
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
			'category' => '警情分类',
            'type' => '编号',
            'content' => '警情内容',
            'suggest' => '处理建议',
            'send_email' => '是否发邮件',
            'send_msg' => '是否发短信',
            'type_value' => '警情判断标准',
            'alarm_type' => '警情类型',
            'operator' => '判断操作符',
			'judge_type' => '警情判断类型',
			'can_ignore' => '处理方法',
			'alarm_code' => '警情编码',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
		);
	}
    public function getAlarmTypeText()
    {
        return $this->alarmaypes[$this->alarm_type];
    }

    public function getOperatorText()
    {
        return isset($this->operator) && $this->operator != '' ?  $this->operators[$this->operator] : '';
    }
    public function getCategory()
    {
        return $this->categorys[$this->category];
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
		$criteria->compare('type_value',$this->type_value,true);
        $criteria->compare('operator',$this->operator,true);
		$criteria->compare('alarm_type',$this->alarm_type,true);
		$criteria->compare('judge_type',$this->judge_type);
		$criteria->compare('alarm_code',$this->alarm_code,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
        $criteria->order = 'category ,type';
		return new CActiveDataProvider($this, array(
            'pagination'=>array(
                'pageSize'=>25,//设置每页显示20条
            ),
            'sort'=>false,
            'criteria'=>$criteria,
		));
	}
}