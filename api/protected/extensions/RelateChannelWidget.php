<?php
class RelateChannelWidget extends CWidget
{
    public $limit = 10;
    public $pid = 0;
    public $cid = 0;

    public function run()
    {
        $Channels = $this->getChannels();
        if (empty($Channels)) 
            return false;
        $html = '<ul>';
        foreach ($Channels as $key => $value) {
            if ($this->pid) {
                $url = Yii::app()->createUrl('channel/view',array('id'=>$value['id']));
            }else{
                $url = Yii::app()->createUrl('channel/class',array('id'=>$value['id']));
            }
            $html .= '<li><a href="'.$url.'">'.$value['title'].'</a></li>';
        }
        $html .=  '</ul>';
        echo $html;
    }
    //获取连接
    public function getChannels(){
        if ($this->cid) {
            $where = 'cid='.$this->cid;
        }else if ($this->pid) {
            $where = 'pid='.$this->pid.' and cid =0';
        }else{
            $where = 'pid=0';
        }
        $allLinks = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{channels}}')
            ->where($where)
            ->order('id asc')
            ->limit($this->limit)
            ->queryAll();

        return $allLinks;
    }
}


