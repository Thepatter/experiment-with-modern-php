<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/31
 * Time: 23:10
 */

namespace PHP_Design_Patterns\Design_Patterns;

include_once 'DoMath.php';
class InheritMath extends DoMath
{
    private $textOut;
    private $fullFace;

    public function numToText($sum)
    {
        $this->textOut = (string)$sum;
        return $this->textOut;
    }
    public function addFace($face, $msg)
    {
        $this->fullFace = "<strong>" . $face . "</strong>:" . $msg;
        return $this->fullFace;
    }
}