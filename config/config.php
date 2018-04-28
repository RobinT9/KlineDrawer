<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/4/25
 * Time: 14:38
 */

return $data = [

    //火币网API密钥
    'ACCOUNT_ID'    =>  '',
    'ACCESS_KEY'    =>  '',
    'SECRET_KEY'    =>  '',

    //报错时的通知EMAIL地址
    'ERROR_EMAIL'   =>  '',

    //定义需要查找的交易对
    'Huobi_Coin'    =>  ['QUN_BTC','ETH_BTC','EOS_BTC','EOS_ETH','QUN_ETH'],
    'ZB_Coin'       =>  ['CDC_BTC','XUC_BTC','DDM_BTC'],

    //SMTP设置
    'SMTP'          =>  [
        'Host'      =>  '',
        'Port'      =>  '',
        'UserName'  =>  '@qq.com',
        'PassWord'  =>  '',
    ],
];