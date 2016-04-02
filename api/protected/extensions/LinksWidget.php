<?php
class LinksWidget extends CWidget
{
    public function run()
    {
        $links = $this->getFlinks();
        if (empty($links)) 
            return false;
        $html = '<ul>';

        foreach ($links as $key => $value) {
            $html .= '<li><a target="_blank" href="'.$value['url'].'">'.$value['name'].'</a></li>';
        }
        
        $html .= '</ul>';

        echo  $html;
    }
    //获取连接
    public function getFlinks(){
        $allLinks = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{links}}')
            ->order('ordernum desc,id asc')
            ->queryAll();

        return $allLinks;
    }
}


