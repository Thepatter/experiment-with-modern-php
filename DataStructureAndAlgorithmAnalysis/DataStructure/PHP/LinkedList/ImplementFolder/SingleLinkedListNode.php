<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:38
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\ImplementFolder;


class SingleLinkedListNode extends LinkedListNode
{
    private $next;

    public function __construct($data)
    {
        static::setData($data);
    }

    public function setNext($next): void
    {
        $this->next = $next;
    }

    public function getNext(): int
    {
        return $this->next;
    }
}