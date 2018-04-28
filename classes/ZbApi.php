<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/4/25
 * Time: 11:19
 */

namespace classes;

class ZbApi implements \Interfaces\CoinApi
{
    use \traits\Bill;
    protected $ZbBaseUrl;
    public $Coins;
    protected $doneBills;

    public function __construct($coins)
    {
        $this->Coins = $coins;
        $this->ZbBaseUrl = 'http://api.zb.com';
        $this->doneBills = array();
    }

    /**
     * @param $coinName  -example:QUN_BTC
     * @return array
     */
    public function getTrade($coinName)
    {
        // TODO: Implement getTrade() method.

        //trans coinName
        $coinName = strtolower($coinName);

        //ApiUrl
        $apiUrl = $this->ZbBaseUrl."/data/v1/trades?market=$coinName";

        $Result = $this->zbcurl($apiUrl);
        if($Result){
            $Result = json_decode($Result, true);
            shuffle($Result);
            $oneTrade = array_shift($Result);
            $trade = [
                "amount"    =>  $oneTrade['amount'],
                "ts"        =>  $oneTrade['date'],
                "id"        =>  $oneTrade['tid'],
                "price"     =>  $oneTrade['amount'],
                "direction" =>  $oneTrade['type'],
            ];
            $all[] = $trade;
            return $all;
        }else{
            return false;
        }
    }

    public function cancleKey($key)
    {
        unset($this->Coins[$key]);
    }

    function zbcurl($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }
    }
}