<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/27
 * Time: 22:48
 */

namespace PHP_Design_Patterns\OOP;


class OneTrick
{
    private $storeHere;
    public function trick($whatever)
    {
        $this->storeHere = $whatever;
        return $this->storeHere;
    }
}
$doIt = new OneTrick();
$dataNow = $doIt->trick("This is perfect");
echo $dataNow;