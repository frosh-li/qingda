<?php
class NewsWidget extends CWidget
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
            $html .= '<li><a href="'.$url.'">'.$value['title'].'</a></li>';
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
            ->order('id desc')
            ->limit($this->limit)
            ->queryAll();

        return $allLinks;
    }
}


