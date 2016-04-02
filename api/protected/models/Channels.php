<?php

/**
 * This is the model class for table "{{channels}}".
 *
 * The followings are the available columns in table '{{channels}}':
 * @property string $id
 * @property string $pid
 * @property integer $channeltype
 * @property integer $systemtype
 * @property string $ordernum
 * @property integer $ishidden
 * @property string $positions
 * @property string $alias
 * @property string $title
 * @property string $content
 * @property string $seotitle
 * @property string $metakeywords
 * @property string $metadesc
 * @property string $link
 * @property integer $target
 * @property string $create_time
 * @property string $update_time
 */
class Channels extends CActiveRecord
{
	const TYPE_PAGE=1; 
	const TYPE_LIST=2;
	const TYPE_LINK=4;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Channels the static model class
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
		return '{{channels}}';
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
        $this->positions = 1;
        $this->channeltype = 2;
        $this->link = '';
        return parent::beforeValidate();
    }
	public function getTypeOptions()
	{
	    return array( 
	        self::TYPE_PAGE=>'单页', 
	        self::TYPE_LIST=>'文章列表',
	        self::TYPE_LINK=>'链接',
	    );
	}
	/**
	 * [getPidOptions description]
	 * @return [type] [description]
	 */
	public function getPidOptions()
	{
		$roles = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{roles}}')
            ->where('uid='.Yii::app()->user->id)
            ->queryAll();
        $rolestr = '';
        $role = array();
        if ($roles) {
        	foreach ($roles as $key => $value) {
        		$role[] = $value['pid'];
        	}
        	$rolestr = implode(',', $role);
        }

		$where = ' pid=0 and ishidden=0';
		if (Yii::app()->user->role != 0) {
			$where .= ' and id in ('.$rolestr.') ';
		}

	    $channels = Yii::app()->db->createCommand()
				    ->select('id,title')
				    ->from('{{channels}}')
				    ->where($where)
				    ->order('ordernum,id')
				    ->queryAll();
		// $options = array(0=>'作为主分类');
        $options = array();
		foreach ($channels as $key => $value) {
			$options[$value['id']]=$value['title'];
		}
	    return $options;
	}
	/**
	 * @return string the type text display for the current type
	 */
	public function getPidText($id=0) {
		if ($id == 0) {
			return '主分类';
		}
		$channels = Yii::app()->db->createCommand()
				    ->select('id,title')
				    ->from('{{channels}}')
				    ->where('id='.$id)
				    ->queryRow();
		if ($channels) {
			return $channels['title'];
		}else{
			return '主分类';
		}
	}
	/**
	 * @return string the type text display for the current type
	 */
	public function getTitle() {
		if ($this->cid != 0 ) {
			$cid = Yii::app()->db->createCommand()
				    ->select('id,title')
				    ->from('{{channels}}')
				    ->where('id='.$this->cid)
				    ->queryRow();
			$id = Yii::app()->db->createCommand()
					    ->select('id,title')
					    ->from('{{channels}}')
					    ->where('id='.$this->id)
					    ->queryRow();
			if ($cid) {
				return $cid['title'].'>'.$id['title'];
			}

		}else{
			$cid = Yii::app()->db->createCommand()
				    ->select('id,title')
				    ->from('{{channels}}')
				    ->where('id='.$this->id)
				    ->queryRow();
			return $cid['title'];
		}
		
	}
	/**
	 * @return string the type text display for the current type
	 */
	public function getTypeText() {
	    $typeOptions = $this->getTypeOptions();
	    return isset($typeOptions[$this->token]) ? $typeOptions[$this->token] :"";
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title,channeltype,ishidden,target, create_time, update_time', 'required'),
			array('channeltype, systemtype, ishidden,cid, target', 'numerical', 'integerOnly'=>true),
			array('pid, ordernum', 'length', 'max'=>10),
			array('title', 'length', 'max'=>2048),
			array('positions, alias', 'length', 'max'=>45),
			array('seotitle, metakeywords, metadesc, link', 'length', 'max'=>255),
			array('create_time, update_time', 'length', 'max'=>11),
			array('content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pid, channeltype, systemtype, ordernum, ishidden, positions, alias, title, content, seotitle, metakeywords, metadesc, link, target, create_time, update_time', 'safe', 'on'=>'search'),
			array('id, pid, channeltype, systemtype, ordernum, ishidden, positions, alias, title, content, seotitle, metakeywords, metadesc, link, target, create_time, update_time', 'safe', 'on'=>'my'),
			array('id, pid, channeltype, systemtype, ordernum, ishidden, positions, alias, title, content, seotitle, metakeywords, metadesc, link, target, create_time, update_time', 'safe', 'on'=>'oupdate'),
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
			'pid' => '主栏目',
			'cid' => '二级栏目',
			'channeltype' => '频道类型',
			'systemtype' => '系统类型',
			'ordernum' => '排序',
			'ishidden' => '是否隐藏',
			'positions' => '位置',
			'alias' => '别名',
			'title' => '名称',
			'content' => '栏目内容',
			'seotitle' => '栏目标题',
			'metakeywords' => '关键词',
			'metadesc' => '描述',
			'link' => '链接',
			'target' => '新页面',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
		);
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function my()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$roles = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{roles}}')
            ->where('uid='.Yii::app()->user->id)
            ->queryAll();
        $role = array();
        if ($roles) {
        	foreach ($roles as $key => $value) {
        		if ($value['channel'] == 0) {
        			$role[] = ' (id='.$value['cid'].') ';
        		}else{
        			$role[] = ' (id='.$value['channel'].') ';
        		}
        	}
        }
        $rolestr = implode(' or ', $role);
		$where = $rolestr.' and ishidden=0';

		$criteria=new CDbCriteria;

		$criteria->addCondition($where); 
		// $criteria->compare('id',$this->id,true);
		// $criteria->compare('pid',$this->pid,true);
		$criteria->compare('channeltype',$this->channeltype);
		$criteria->compare('systemtype',$this->systemtype);
		$criteria->compare('ordernum',$this->ordernum,true);
		$criteria->compare('ishidden',0);
		$criteria->compare('positions',$this->positions,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('seotitle',$this->seotitle,true);
		$criteria->compare('metakeywords',$this->metakeywords,true);
		$criteria->compare('metadesc',$this->metadesc,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('target',$this->target);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

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
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($flag=0)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$roles = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{roles}}')
            ->where('uid='.Yii::app()->user->id)
            ->queryAll();
        

		$criteria=new CDbCriteria;

		$rolestr = '';
        $role = array();
		if ($flag == 1) {
	        if ($roles) {
	        	foreach ($roles as $key => $value) {
	        		$role[] = $value['pid'];
	        	}
	        	$rolestr = implode(',', $role);
	        }

			$where = ' pid=0 and ishidden=0';
			if (Yii::app()->user->role != 0) {
				$where .= ' and id in ('.$rolestr.') ';
			}
			$criteria->addCondition($where); 
		}

		if ($flag == 2) {
			if ($roles) {
	        	foreach ($roles as $key => $value) {
	        		$role[] = $value['cid'];
	        	}
	        	$rolestr = implode(',', $role);
	        }

			$where = ' pid!=0 and cid=0 and ishidden=0';
			if (Yii::app()->user->role != 0) {
				$where .= ' and id in ('.$rolestr.') ';
			}
			$criteria->addCondition($where); 
		}
		if ($flag == 3) {
			if ($roles) {
	        	foreach ($roles as $key => $value) {
	        		$role[] = $value['channel'];
	        	}
	        	$rolestr = implode(',', $role);
	        }

			$where = ' pid!=0 and cid!=0 and ishidden=0';
			if (Yii::app()->user->role != 0) {
				$where .= ' and id in ('.$rolestr.') ';
			}
			$criteria->addCondition($where); 
		}
		// $criteria->compare('id',$this->id,true);
		// $criteria->compare('pid',$this->pid,true);
		$criteria->compare('channeltype',$this->channeltype);
		$criteria->compare('systemtype',$this->systemtype);
		$criteria->compare('ordernum',$this->ordernum,true);
		$criteria->compare('ishidden',0);
		$criteria->compare('positions',$this->positions,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('seotitle',$this->seotitle,true);
		$criteria->compare('metakeywords',$this->metakeywords,true);
		$criteria->compare('metadesc',$this->metadesc,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('target',$this->target);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

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