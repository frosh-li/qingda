<?php
class NewInfoWidget extends CWidget
{
    public $limit = 4;
    public $cid = '';

    public function run()
    {
        $News = $this->getNews();
        if (empty($News)) 
            return false;
        $html = '<ul>';
        foreach ($News as $key => $value) {
            $url = Yii::app()->createUrl('article/view',array('id'=>$value['id']));
            if($value['type'] == 1){
                $html .= '<li><span class="i-time">'.date('Y-m-d',$value['create_time']).'</span><a href="'.$url.'"><font color="blue">'.$value['title'].'</font></a></li>';
            }else{
                $html .= '<li><span class="i-time">'.date('Y-m-d',$value['create_time']).'</span><a href="'.$url.'">'.$value['title'].'</a></li>';
            }
        }
        $html .=  '</ul>';
        echo $html;
    }
    //获取连接
    public function getNews(){
        $allLinks = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{articles}}')
            ->where('cid='.$this->cid)
            ->order('type desc ,id desc')
            ->limit($this->limit)
            ->queryAll();

        return $allLinks;
    }
}


