<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:31
 */

namespace DataStructureAndAlgorithmAnalysis\PHPImplement\LinkedListData;

abstract class LinkedListNode
{
    protected $data;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}