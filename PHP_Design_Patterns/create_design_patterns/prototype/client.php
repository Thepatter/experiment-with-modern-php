<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/8
 * Time: 22:09
 */
//namespace PHP_Design_Patterns\create_design_patterns\prototype;

//use PHP_Design_Patterns\create_design_patterns\prototype\FemaleProto;
//use PHP_Design_Patterns\create_design_patterns\prototype\MaleProto;
//use PHP_Design_Patterns\create_design_patterns\prototype\IPrototype;

function __autoload($class_name)
{
    //include 'PHP_Design_Patterns\create_design_patterns\prototype' . $class_name . '.php';
    include $class_name . '.php';
}
class Client
{
    // 用于直接实例化
    private $fly1;
    private $fly2;
    // 用于克隆
    private $c1Fly;
    private $c2Fly;
    private $updatedCloneFLy;

    public function __construct()
    {
        // 实例化
        $this->fly1 = new MaleProto();
        $this->fly2 = new FemaleProto();
        // 克隆
        $this->c1Fly = clone $this->fly1;
        $this->c2Fly = clone $this->fly2;
        $this->updatedCloneFLy = clone $this->fly2;
        // 更新克隆
        $this->c2Fly->mated = 'true';
        $this->c2Fly->fecundity = "186";
        $this->updatedCloneFLy->eyeColor = "purple";
        $this->updatedCloneFLy->wingBeat = "220";
        $this->updatedCloneFLy->unitEyes = "750";
        $this->updatedCloneFLy->fecundity = "92";
        // 通过类型提示方法发送
        $this->showFly($this->c1Fly);
        $this->showFly($this->c2Fly);
        $this->showFly($this->updatedCloneFLy);
    }

    public function showFly(IPrototype $fly)
    {
        echo "Eye color: " . $fly->eyeColor . "<br/>";
        echo "Wing Beats/second: " . $fly->wingBeat . "<br/>";
        echo "Eye untis: " . $fly->unitEyes . "</br>";
        $genderNow = $fly::gender;
        echo "Gender:: " . $genderNow . "<br/>";
        if ($genderNow == 'FEMALE') {
            echo "Number of eggs: " . $fly->fecundity . "<p/>";
        } else {
            echo "Mated: " . $fly->mated . "</p>";
        }
    }
}
$worker = new client();