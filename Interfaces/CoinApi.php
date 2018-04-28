<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/4/25
 * Time: 11:17
 */

namespace Interfaces;


interface CoinApi
{
    public function getTrade($coinName);
    public function cancleKey($key);
}