<?php

function __autoload($class_name)
{
    include $class_name . '.php';
}

class Client
{
    private $market;
    private $manage;
    private $engineer;

    public function __construct()
    {
        $this->makeConProto();

        $Tess = clone $this->market;
        $this->setEmployee($Tess, 'Tess Smith', 101, 'ts101_1234', 'tess.png');
        $this->showEmployee($Tess);

        $jacob = clone $this->market;
        $this->setEmployee($jacob, 'Jacob jones', 102, 'jj101_2234', 'jacob.png');
        $this->showEmployee($jacob);

        $Ricky = clone $this->manage;
        $this->setEmployee($Ricky, 'Ricky Rodriguez', 203, 'rr203-5634', 'ricky.png');
        $this->showEmployee($Ricky);

        $Olivia = clone $this->engineer;
        $this->setEmployee($Olivia, 'Olivia Perez', 302, 'op301-1278', 'olivia.png');
        $this->showEmployee($Olivia);

        $John = clone $this->engineer;
        $this->setEmployee($John, 'John Jackson', 301, 'jj302-1454', 'john.png');
        $this->showEmployee($John);
    }

    private function makeConProto()
    {
        $this->market = new Marketing;
        $this->manage = new Management();
        $this->engineer = new Engineering();
    }

    private function showEmployee(IAcmePrototype $employeeNow)
    {
        $px = $employeeNow->getPic();
        echo "<img src=$px width='150' height='150'><br/>>";
        echo $employeeNow->getName() . '<br/>';
        echo $employeeNow->getDept() . ':' . $employeeNow::UNIT . '<br/>';
        echo $employeeNow->getID() . "<p/>";
    }

    private function setEmployee(IAcmePrototype $employeeNow, $nm, $dp, $id, $px)
    {
        $employeeNow->setName($nm);
        $employeeNow->setDept($dp);
        $employeeNow->setId($id);
        $employeeNow->setPic("pix/$px");
    }
}

$worler = new Client();