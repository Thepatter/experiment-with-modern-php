<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:39
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\ImplementFolder;

use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\AbstractClassFolder\LinkedListNode;
use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder\DoubleLinkedListNode as DoubleLinkedListNodeInterface;

class DoubleLinkedListNode extends LinkedListNode implements DoubleLinkedListNodeInterface
{
    private $prev;

    private $next;

    public function __construct($data)
    {
        static::setData($data);
    }

    public function setPrev(int $prev): void
    {
        $this->prev = $prev;
    }

    public function setNext(int $next): void
    {
        $this->next = $next;
    }

    public function getPrev(): int
    {
        return $this->prev;
    }

    public function getNext(): int
    {
        return $this->next;
    }
}