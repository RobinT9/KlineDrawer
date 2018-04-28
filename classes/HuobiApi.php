<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/4/25
 * Time: 11:19
 */

namespace classes;

include "lib.php";
class HuobiApi implements \Interfaces\CoinApi
{
    use \traits\Bill;
    protected $HproApi;
    public $Coins;

    public function __construct($coins)
    {
        $this->Coins = $coins;
        $this->HproApi = new \req();
        $this->doneBills = array();
    }

    /**
     * @param $coinName  -example:QUN_BTC
     * @return array
     */
    public function getTrade($coinName)
    {
        // TODO: Implement getTrade() method.

        //trans CoinName to lower like qunbtc
        $coinName = str_replace('_','',$coinName);
        $coinName = strtolower($coinName);

        $Result = $this->HproApi->get_market_trade($coinName);
        $Result = json_decode(json_encode($Result), true);
        if($Result['status']=='ok') {
            $data = $Result['tick']['data'];
            return $data;
        }else{
            return false;
        }
    }

    public function cancleKey($key)
    {
        unset($this->Coins[$key]);
    }
}