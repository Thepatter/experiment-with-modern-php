<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/31
 * Time: 23:22
 */

namespace PHP_Design_Patterns\Design_Patterns;

/**
 * Class DoText
 * @package PHP_Design_Patterns\Design_Patterns
 * 组合实现
 */
class DoText
{
    private $textOut;
    private $fullFace;
    public function numToText($num)
    {
        $this->textOut = (string) $num;
        return $this->textOut;
    }
    public function addFace($face, $msg)
    {
        $this->fullFace = "<strong>" . $face . "</strong>:" . $msg;
        return $this->fullFace;
    }
}