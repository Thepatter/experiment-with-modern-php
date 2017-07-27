<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/27
 * Time: 23:02
 */

namespace PHP_Design_Patterns\OOP;

include 'OneTrickAbstract.php';
class OneTrickConcrete extends OneTrickAbstract
{
    public function trick($whatever)
    {
        // TODO: Implement trick() method.
        $this->storeHere = "An abstract property";
        return $whatever . $this->storeHere;
    }
}

$worker = new OneTrickConcrete();
echo $worker->trick("From an abstract origin...");