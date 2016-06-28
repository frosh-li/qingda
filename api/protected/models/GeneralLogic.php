<?php

/**
 * This is the Logic class for table "{{GeneralLogic}}".
 * @date 2016/06/25 14:30、
 * @author xl
 *
 */
class GeneralLogic
{

    CONST SUPER_ROLE = 1;
    
    CONST ADMIN_ROLE = 2;
    
    CONST WATCH_ROLE = 3;
    
    public static function getUserInfo($uid, $is_role = true) {
        $uid = intval($uid);
        $sql = "select * from {{sysuser}} where id='" . $uid . "'";
        $user_info = Yii::app()->db->createCommand($sql)->queryRow();
        if(!empty($user_info['area'])){
            $user_info['areas'] = explode(",", $user_info['area']);
        }
        
        if ($is_role) {
            $sql = "select * from {{roles}}";
            $row = Yii::app()->db->createCommand($sql)->queryAll();
            if (empty($row)) {
                return array();
            }
            
            $role_name = '';
            foreach ($row as $v) {
                if ($v['id'] == $user_info['role']) {
                    $role_name = $v['rolename'];
                    break;
                }
            }
            
            switch ($role_name) {
                case '超级管理员' :
                    $user_info['role_cus'] = self::SUPER_ROLE;
                break;
                case '管理员' :
                    $user_info['role_cus'] = self::ADMIN_ROLE;
                break;
                
                case '观察员' :
                    $user_info['role_cus'] = self::WATCH_ROLE;
                break;
            }
        }
        return empty($user_info) ? array() : $user_info;
    }
    
    public static function filterDataByAid($uid, $sites){
        if(empty($sites)){
            return array();
        }
        
        //观察员进行地域过滤 xl
        $user_info = self::getUserInfo($uid);
        if(!empty($user_info['role_cus']) && $user_info['role_cus'] == GeneralLogic::ADMIN_ROLE){
            foreach($sites as $index => $site){
                if(!in_array($site['aid'], $user_info['areas'])){
                    unset($sites[$index]);
                }
            }
            $sites = empty($sites) ? array() : array_values($sites);
        }
        
        return $sites;
    }
    
    public static function filterDataBySn($uid, $sites){
        if(empty($sites)){
            return array();
        }
    
        //观察员进行地域过滤 xl
        $sns = self::getWatchSeriNumByAid($uid);
        if($sns == false){
            return $sites;
        }
        foreach($sns as $sn){
            $sns_new[] = substr($sn, 0, 10);
        }
        if(!empty($sns_new)){
            foreach($sites as $index => $site){
                if(!in_array(substr($site['sn_key'], 0, 10), $sns_new)){
                    unset($sites[$index]);
                }
            }
            $sites = empty($sites) ? array() : array_values($sites);
        }
        return $sites;
    }
    
    public static function isWatcher($uid){
        if(empty($uid)){
            return array();
        }
    
        //观察员进行地域过滤 xl
        $user_info = self::getUserInfo($uid);
        if(!empty($user_info['role_cus']) && $user_info['role_cus'] == GeneralLogic::ADMIN_ROLE){
            return $user_info;
        }
    
        return array();
    }
    
    public static function getWatchSeriNumByAid($uid){
        if(empty($uid)){
            return array();
        }
    
        //观察员进行地域过滤 xl
        $user_info = self::getUserInfo($uid);
        if(!empty($user_info['role_cus']) && $user_info['role_cus'] == GeneralLogic::ADMIN_ROLE){
            $sql = "select serial_number  from my_site where aid in(" .$user_info['area'] .")";
            $sns = Yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($sns)){
                foreach($sns as $sn){
                    $data[] = $sn['serial_number'];
                }
                return $data;
            }
            return array();
        }
    
        return false;
    }

}