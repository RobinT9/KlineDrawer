<?php
use classes\HuobiApi;
use classes\ZbApi;
use classes\Config;

$config = Config::get();
// 定义参数
define('ACCOUNT_ID', $config['ACCOUNT_ID']); // 你的账户ID
define('ACCESS_KEY',$config['ACCESS_KEY']); // 你的ACCESS_KEY
define('SECRET_KEY', $config['SECRET_KEY']); // 你的SECRET_KEY

    //实例化类库
    $Huo    = new HuobiApi($config['Huobi_Coin']);
    $ZB     = new ZbApi($config['ZB_Coin']);

    while(true){
        /** 火币订单 **/
        $Huocoin = $Huo->Coins;//['QUN_BTC','ETH_BTC','EOS_BTC','EOS_ETH','QUN_ETH'];
        foreach ($Huocoin as $c){
            $data = $Huo->getTrade($c);

            if(!$data){
                //火币接口报错了
                $msg = "[ ERROR ] Huobi [$c]Api got Error \n";
                send_email($config['ERROR_EMAIL'],'[ ZBApi ERROR ]',$msg);
                echo $msg;
                continue;
            }
            //循环订单
            foreach ($data as $d){
                //如果订单已经存在了的话继续循环
                if($Huo->InBill($d['id'])){
                    echo "[ Order repeat ] Huobi [ $c ] OrderId ".$d['id']." already exist \n";
                    continue;
                }
                //判断买卖关系，确定挂单和吃掉挂单的买卖方式
                switch ($d['direction']){
                    case 'buy':
                        $type = 2;
                        $callbackType = 1;
                        break;
                    case 'sell':
                        $type = 1;
                        $callbackType = 2;
                        break;
                }

                $price = $d['price'];
                //匹配价格是否带E
                if(preg_match('/[E]/',$price)){
                    $price = sctonum($price,8);
                }
                //价格保留6位小数
                $price = round($price,6);
                //数量保留5位小数
                $number = $d['amount'];
                $number = round($number,5);

                //开始挂单
                $curlRes = coin_curl($c,$type,$price,$number);
                if(!$curlRes){
                    //接口调用失败
                }

                $curlRes = json_decode($curlRes,true);
                if($curlRes['success']==true){
                    //接口正常挂单成功，进行反向交易吃掉挂单
                    $callBackResult = coin_curl($c,$callbackType,$price,$number);
                    if(!$callBackResult){
                        //接口报错
                    }
                    $callBackResult = json_decode($callBackResult,true);
                    if($callBackResult['success']==false){
                        echo "[ CallBackApi ERROR ] $c \n";
                        //接口返回false，第一次挂单失败，分析是否币不足，正则匹配 [不足] 两个字
                        if(strstr($curlRes['msg'],'不足')){
                            //从对象中删除这个币
                            $key = array_search($c,$Huocoin);
                            $Huo->cancleKey($key);
                            //发送邮件通知
                            $msg = "[ Lack Coin ] Huobi [$c] Coin is running out \n";
                            send_email($config['ERROR_EMAIL'],'[ CallBackApi ERROR ]',$msg);

                        }else{
                            //发送邮件通知
                            send_email($config['ERROR_EMAIL'],'[ CallBackApi ERROR ]',$curlRes['msg']);
                        }
                        continue;
                    }else{
                        //成功了，输出统计信息，把订单号暂存
                        echo "Id:[ ".$d['id']." ] Action:[ ".$d['direction']." ] [ ".$c." ] Price:[ ".$price." ]Number:[ ".$number." ] At [".$d['ts']."]\n";
                        $Huo->addBill($d['id']);
                    }

                }else{
                    echo "[ LocalApi ERROR ] $c \n";
                    //接口返回false，第一次挂单失败，分析是否币不足，正则匹配不足两个字
                    if(strstr($curlRes['msg'],'不足')){
                        //从对象中删除这个币
                        $key = array_search($c,$Huocoin);
                        $Huo->cancleKey($key);
                        //发送邮件通知
                        $msg = "[ Lack Coin ] Huobi [$c] Coin is running out \n";
                        send_email($config['ERROR_EMAIL'],'[ CallBackApi ERROR ]',$msg);

                    }else{
                        //其他失败原因，发送邮件通知
                        send_email($config['ERROR_EMAIL'],'[ CallBackApi ERROR ]',$curlRes['msg']);
                    }
                    continue;//继续循环
                }

            }
        }

        /** zb订单 **/
    //        $Zbcoin = $ZB->Coins;//['QUN_BTC','ETH_BTC','EOS_BTC','EOS_ETH','QUN_ETH'];
    //        foreach ($Zbcoin as $c){
    //            $data = $ZB->getTrade($c);
    //
    //            if(!$data){
    //                //zb接口报错了
    //                $msg = "[ ERROR ] ZB[$c]Api got Error \n";
    //                send_email($config['ERROR_EMAIL'],'[ ZBApi ERROR ]',$msg);
    //                echo $msg;
    //                continue;
    //            }
    //            //循环订单
    //            foreach ($data as $d){
    //                //如果订单已经存在了的话继续循环
    //                if($ZB->InBill($d['id'])){
    //                    echo "[ Order repeat ] ZB OrderId ".$d['id']." already exist \n";
    //                    continue;
    //                }
    //                //判断买卖关系，确定挂单和吃掉挂单的买卖方式
    //                switch ($d['direction']){
    //                    case 'buy':
    //                        $type = 2;
    //                        $callbackType = 1;
    //                        break;
    //                    case 'sell':
    //                        $type = 1;
    //                        $callbackType = 2;
    //                        break;
    //                }
    //
    //                $price = $d['price'];
    //                //匹配价格是否带E
    //                if(preg_match('/[E]/',$price)){
    //                    $price = sctonum($price,8);
    //                }
    //                //价格保留6位小数
    //                $price = round($price,6);
    //                //数量保留5位小数
    //                $number = $d['amount'];
    //                $number = round($number,5);
    //
    //                //开始挂单
    //                $curlRes = coin_curl($c,$type,$price,$number);
    //                if(!$curlRes){
    //                    //接口调用失败
    //                }
    //
    //                $curlRes = json_decode($curlRes,true);
    //                if($curlRes['success']==true){
    //                    //接口正常挂单成功，进行反向交易吃掉挂单
    //                    $callBackResult = coin_curl($c,$callbackType,$price,$number);
    //                    if(!$callBackResult){
    //                        //接口报错
    //                    }
    //                    $callBackResult = json_decode($callBackResult,true);
    //                    if($callBackResult['success']==false){
    //                        echo "[ CallBackApi ERROR ] $c \n";
    //                        //接口返回false，第一次挂单失败，分析是否币不足，正则匹配 [不足] 两个字
    //                        if(strstr($curlRes['msg'],'不足')){
    //                            //从对象中删除这个币
    //                            $key = array_search($c,$Zbcoin);
    //                            $ZB->cancleKey($key);
    //                            //发送邮件通知
    //                            $msg = "[ Lack Coin ] ZB[$c] Coin is running out \n";
    //                            send_email($config['ERROR_EMAIL'],'[ CallBackApi ERROR ]',$msg);
    //
    //                        }else{
    //                            //发送邮件通知
    //                            send_email($config['ERROR_EMAIL'],'[ CallBackApi ERROR ]',$curlRes['msg']);
    //                        }
    //                        continue;
    //                    }else{
    //                        //成功了，输出统计信息，把订单号暂存
    //                        echo "Id:[ ".$d['id']." ] Action:[ ".$d['direction']." ] [ ".$c." ] Price:[ ".$price." ]Number:[ ".$number." ] At [".$d['ts']."]\n";
    //                        $ZB->addBill($d['id']);
    //                    }
    //
    //                }else{
    //                    echo "[ LocalApi ERROR ] $c \n";
    //                    //接口返回false，第一次挂单失败，分析是否币不足，正则匹配不足两个字
    //                    if(strstr($curlRes['msg'],'不足')){
    //                        //从对象中删除这个币
    //                        $key = array_search($c,$Zbcoin);
    //                        $ZB->cancleKey($key);
    //                        //发送邮件通知
    //                        $msg = "[ Lack Coin ] ZB[$c] Coin is running out \n";
    //                        send_email($config['ERROR_EMAIL'],'[ CallBackApi ERROR ]',$msg);
    //
    //                    }else{
    //                        //其他失败原因，发送邮件通知
    //                        send_email($config['ERROR_EMAIL'],'[ CallBackApi ERROR ]',$curlRes['msg']);
    //                    }
    //                    continue;//继续循环
    //                }
    //
    //            }
    //        }

        sleep(10);

    }