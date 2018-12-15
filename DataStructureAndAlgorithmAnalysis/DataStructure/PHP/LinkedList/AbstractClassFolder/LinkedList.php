<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:24
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\AbstractClassFolder;

use Iterator;

abstract class LinkedList
{
    protected $linkedListLength;

    protected $linkedListArray = [];

    protected $linkedCurrentPoint = 0;

    public function getLinkedListLength()
    {
        return $this->linkedListLength;
    }

    public function current()
    {
        // TODO: Implement current() method.
        return $this->linkedListArray[$this->linkedCurrentPoint];
    }

    public function key()
    {
        // TODO: Implement key() method.
        return $this->linkedCurrentPoint;
    }

    public function next()
    {
        // TODO: Implement next() method.
        return $this->linkedCurrentPoint++;
    }

    public function valid()
    {
        // TODO: Implement valid() method.
        return $this->linkedCurrentPoint < $this->linkedListLength;
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
        $this->linkedCurrentPoint = 0;
    }

    /**
     * @return array
     */
    public function toArray() :array
    {
        return $this->linkedListArray;
    }


}