<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/4/25
 * Time: 15:35
 */
namespace classes;

class Config
{
    public static function get($keyword = '')
    {
        $data = include_once CONFIG_PATH;
        if(!empty($keyword)){
            return $data[$keyword];
        }else{
            return $data;
        }
    }
}