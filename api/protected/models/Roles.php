<?php

/**
 * This is the model class for table "{{roles}}".
 *
 * The followings are the available columns in table '{{roles}}':
 * @property string $id
 * @property string $uid
 * @property string $pid
 * @property string $cid
 * @property string $channel
 * @property string $create_time
 * @property integer $update_time
 */
class Roles extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Roles the static model class
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
		return '{{roles}}';
	}
	/**
	 * [beforeValidate :Prepares created and updated  attributes before performing validation.]
	 * @return [type]
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
			array('username,pid, create_time, update_time', 'required'),
			array('update_time', 'numerical', 'integerOnly'=>true),
			// array('uid,pid,cid,channel','unique'),
			array('uid, pid, cid, channel, create_time', 'length', 'max'=>11),
			array('username', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, pid, cid, channel, create_time, update_time', 'safe', 'on'=>'search'),
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
			'uid' => '用户ID',
			'username' => '用户名',
			'pid' => '一级分类',
			'cid' => '二级分类',
			'channel' => '三级分类',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
		);
	}
	public function getPidText()
	{
		$channel = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{channels}}')
            ->where('id='.$this->pid)
            ->queryRow();

        return $channel['title'];
	}
	public function getCidText()
	{
		$channel = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{channels}}')
            ->where('id='.$this->cid)
            ->queryRow();
       	$ret = isset($channel['title']) ? $channel['title'] : '全部';
       	return $ret;
	}
	public function getChannelText()
	{
		$channel = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{channels}}')
            ->where('id='.$this->channel)
            ->queryRow();
       	$ret = isset($channel['title']) ? $channel['title'] : '全部';
       	return $ret;
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($uid=0)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
       
        if ($uid) {
			$criteria->compare('uid',$uid);
		}
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('cid',$this->cid,true);
		$criteria->compare('channel',$this->channel,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'pagination'=>array(
	            'pageSize'=>20,//设置每页显示20条
	        ),
	        /*
	        'sort'=>array(
	            'defaultOrder'=>'create_time DESC', //设置默认排序是create_time倒序
	        ),
	        */
	       	'sort'=>false,
			'criteria'=>$criteria,
		));
	}
}
