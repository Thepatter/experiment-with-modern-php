<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:38
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\ImplementFolder;

use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\AbstractClassFolder\LinkedListNode;
use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder\SingleLinkedListNode as SingleLinkedListNodeInterface;

class SingleLinkedListNode extends LinkedListNode implements SingleLinkedListNodeInterface
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