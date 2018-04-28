<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/4/25
 * Time: 15:59
 */

define('CONFIG_PATH',__DIR__.'/config/config.php');

spl_autoload_register(function ($class_name) {
    $class_name = explode('\\',$class_name);
    $class_name = implode('/',$class_name);
    require_once $class_name . '.php';
});

include "function.php";
include "vendor/autoload.php";