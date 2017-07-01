<?php
class FocusWidget extends CWidget
{
    public $limit = 5;
    public $class = '';

    public function run()
    {
        $focus = $this->getFocus();
        if (empty($focus)) 
            return false;
         $html = '<div class="pager_'.$this->class.'_b">
                <div class="'.$this->class.'_bd"><div class="tempWrap"><ul >';
        foreach ($focus as $key => $value) {
            $imageurl = $value['image'];
            if (strpos($value['image'],'http') === false) {
                $imageurl = Yii::app()->baseUrl . $value['image'];
            }
            $html .=  '<li><img src="'.$imageurl.'"></li>';
        }
        $html .= '</ul></div></div><div class="'.$this->class.'_hd"><ul>';
        $count = count($focus);
        $i = 0;
        while ( $i< $count ) {
            $html .= '<li>'.$i.'</li>';
            $i++;
        }
        $html .='</ul></div></div>';
        echo $html;
    }
    //获取连接
    public function getFocus($limit = 0){
        $allLinks = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{slideshow}}')
            ->order('sortnum desc,id desc')
            ->queryAll();

        return $allLinks;
    }
}


