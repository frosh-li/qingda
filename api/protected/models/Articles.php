<?php

/**
 * This is the model class for table "{{articles}}".
 *
 * The followings are the available columns in table '{{articles}}':
 * @property string $id
 * @property string $pid
 * @property integer $type
 * @property string $cid
 * @property string $hits
 * @property string $create_time
 * @property string $update_time
 * @property string $picid
 * @property string $picpath
 * @property string $alias
 * @property string $title
 * @property string $content
 * @property string $tag
 * @property string $seotitle
 * @property string $metakeywords
 * @property string $metadesc
 * @property string $userid
 */
class Articles extends CActiveRecord
{
	public static $pidarr = array();
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Articles the static model class
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
		return '{{articles}}';
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
		$this->userid = Yii::app()->user->id;
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
			array('pid,cid,title,type, update_time, content', 'required'),
			array('type,scid', 'numerical', 'integerOnly'=>true),
			array('pid, cid, hits, create_time, picid, userid', 'length', 'max'=>10),
			array('update_time', 'length', 'max'=>11),
			array('picpath', 'length', 'max'=>45),
			array('alias, title, seotitle, metakeywords, metadesc', 'length', 'max'=>255),
			array('tag', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pid, type, cid, hits, create_time, update_time, picid, picpath, alias, title, content, tag, seotitle, metakeywords, metadesc, userid', 'safe', 'on'=>'search'),
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
			'cate' =>array(self::BELONGS_TO, 'Channels', 'pid'),
		);
	}

	public function getPids()
	{
		$sql = " select * from {{channels}} WHERE  pid=0";
		$pids = Yii::app()->db->createCommand($sql)->queryAll();
		$ret = array();
		if($pids){
			foreach ($pids as $key => $value) {
				$ret[$value['id']] = $value['title'];
			}
		}
		self::$pidarr = $ret;
	}

	public function getPidName()
	{
		if(!count(self::$pidarr)){
			$this->getPids();
		}
		return isset(self::$pidarr[$this->pid]) ? self::$pidarr[$this->pid] : '';
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pid' => '主分类',
			'type' => '置顶',
			'cid' => '二级分类',
			'scid' => '三级分类',
			'hits' => '点击',
			'posttime' => '发布时间',
			'picid' => 'Picid',
			'picpath' => '缩略图路径',
			'alias' => 'Alias',
			'title' => '标题',
			'content' => '内容',
			'tag' => '标签',
			'seotitle' => 'SEO标题',
			'metakeywords' => '关键词',
			'metadesc' => '描述',
			'userid' => '用户id',
		);
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function home($id=0)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('hits',$this->hits,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('picid',$this->picid,true);
		$criteria->compare('picpath',$this->picpath,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('seotitle',$this->seotitle,true);
		$criteria->compare('metakeywords',$this->metakeywords,true);
		$criteria->compare('metadesc',$this->metadesc,true);
		if ($id != 0) {
			$criteria->compare('cid',$id,true);
		}
		$criteria->order = 'id desc';
		return new CActiveDataProvider($this, array(
			
			'pagination'=>array(
	            'pageSize'=>20,//设置每页显示20条
	        ),

	        'sort'=>array(
	            'defaultOrder'=>'create_time DESC', //设置默认排序是create_time倒序
	        ),
	        
			'sort'=>false,
			'criteria'=>$criteria,
		));
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($userid=0)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('type',$this->type);
		$criteria->compare('cid',$this->cid,true);
		$criteria->compare('hits',$this->hits,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('picid',$this->picid,true);
		$criteria->compare('picpath',$this->picpath,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('seotitle',$this->seotitle,true);
		$criteria->compare('metakeywords',$this->metakeywords,true);
		$criteria->compare('metadesc',$this->metadesc,true);
		if ($userid != 0) {
			$criteria->compare('userid',$userid,true);
		}
		//$criteria->with = array('cate');
		$criteria->addCondition('pid != 61');
		$criteria->order = 'id desc';

		return new CActiveDataProvider($this, array(
			
			'pagination'=>array(
	            'pageSize'=>20,//设置每页显示20条
	        ),

	        'sort'=>array(
	            'defaultOrder'=>'create_time DESC', //设置默认排序是create_time倒序
	        ),
	        
			'sort'=>false,
			'criteria'=>$criteria,
		));
	}
}