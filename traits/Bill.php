<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/4/8
 * Time: 11:27
 */

namespace traits;

trait Bill
{
    protected $doneBills;

    public function addBill($billId)
    {
        if(count($this->doneBills)>400){
            $this->doneBills[] = [];
        }
        $this->doneBills[] = $billId;
    }

    public function InBill($billId)
    {
        if(in_array($billId,$this->doneBills)){
            return true;
        }else{
            return false;
        }
    }


}