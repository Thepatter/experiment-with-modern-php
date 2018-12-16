<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:31
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\AbstractClassFolder;

use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder\LinkedListNode as LinkedListNodeInterface;

abstract class LinkedListNode implements LinkedListNodeInterface
{
    protected $data;

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}