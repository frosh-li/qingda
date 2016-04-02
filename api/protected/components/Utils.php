<?php
/**
* Utils
*/
class Utils
{
	/**
    *生成验证码
    *@int $n 生成验证码的长度
    **/
    public static function createCode($n = 6)
    {
        $s = '';
        //   输出字符集  
        $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWZYZ";   
        $len = strlen($str)-1;
        for($i=0 ; $i<$n; $i++){
            $s .=  $str[rand(0,$len)];  
        }
        
        return $s;
    }
    // build insert sql
    public static function buildInsertSQL($data)
    {
        $ret = '('.implode(',', array_keys($data)).") VALUES ('".implode("','", $data)."')";
        return $ret;
    }
    // build update sql
    public static function buildUpdateSQL($data)
    {
        $ret = '' ;
        $retarr = array();
        foreach ($data as $key => $value) {
            $retarr[] = $key."='".$value."'";
        }
        $ret = implode(',', $retarr);

        return $ret;
    }
    //获取真实ip
    public static function get_real_ip(){
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip){ 
                array_unshift($ips, $ip); 
                $ip = FALSE; 
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match ('/^(10|172\.16|192\.168)\./', $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
    /**
    *比较函数
    **/
	public function comp($a, $b)
	{
		if ($a['create_time'] > $b['create_time']) 
			return -1; 
		else if ($a['create_time'] == $b['create_time']) 
			return 0; 
		else 
			return 1; 
	}
    /**
     * 截取编码为utf8的字符串
     *
     * @param string $strings 预处理字符串
     * @param int $start 开始处 eg:0
     * @param int $length 截取长度
     * @return unknown
     */
    public static function subString($strings,$start,$length)
    {
        $str = substr($strings, $start, $length);
        $char = 0;
        for ($i = 0; $i < strlen($str); $i++)
        {
            if (ord($str[$i]) >= 128)
            $char++;
        }
        $str2 = substr($strings, $start, $length+1);
        $str3 = substr($strings, $start, $length+2);
        if ($char % 3 == 1)
        {
            if ($length <= strlen($strings))
            {
                $str3 = $str3 .= '...';
            }
            return $str3;
        }
        if ($char%3 == 2)
        {
            if ($length <= strlen($strings))
            {
                $str2 = $str2 .= '...';
            }
            return $str2;
        }
        if ($char%3 == 0)
        {
            if ($length <= strlen($strings))
            {
                $str = $str .= '...';
            }
            return $str;
        }
    }
	/**
    * $filename: 源图片
    * $targetfile: 保存图片位置
    * $maxwidth: 保存图片宽
    * $maxheight:   保存图片高
    * demo:        resizeimage('./1.jpg', './2.jpg', 500, 250);
    */
    public static function resizeimage($filename, $targetfile , $maxwidth, $maxheight){
        $size = getimagesize($filename);
        switch($size[2]){ 
            case 1: 
            $im = @imagecreatefromgif($filename); 
            break; 
            case 2: 
            $im = @imagecreatefromjpeg($filename); 
            break; 
            case 3: 
            $im = @imagecreatefrompng($filename); 
            break; 
        }
        $width   = imagesx($im);
        $height = imagesy($im);
        if(($maxwidth && $width > $maxwidth) && ($maxheight && $height > $maxheight)){
            if($maxwidth && $width > $maxwidth){
                $widthratio = $maxwidth/$width;
                $resizewidth = true;
            }
            if($maxheight && $height > $maxheight){
                $heightratio = $maxheight/$height;
                $resizeheight = true;
            }
            if($resizewidth && $resizeheight){
                if($widthratio < $heightratio){
                    $ratio = $widthratio;
                }else{
                    $ratio = $heightratio;
                }
            }elseif($resizewidth){
                $ratio = $widthratio;
            }elseif($resizeheight){
                $ratio = $heightratio;
            }
            $newwidth = $width * $ratio;
            $newheight = $height * $ratio;
            if(function_exists("imagecopyresampled")){
                $newim = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            }else{
                $newim = imagecreate($newwidth, $newheight);
                imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            }
            imagejpeg($newim, $targetfile ? $targetfile : $filename);
            imagedestroy($newim);
            return TRUE;
        }else{
            return false;
        }
    }
}
?>