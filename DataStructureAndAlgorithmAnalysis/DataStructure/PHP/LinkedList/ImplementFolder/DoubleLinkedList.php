<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:36
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\ImplementFolder;

use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\AbstractClassFolder\LinkedList;
use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder\DoubleLinkedList as DoubleLinkedListInterface;
use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder\DoubleLinkedListNode;

class DoubleLinkedList extends LinkedList implements DoubleLinkedListInterface
{
    private $isExtends;

    private $expansionFactor;

    private $currentLinkedListLength;

    public function __construct(int $linkedListLength = 10, bool $isExtends = false, float $expansionFactor = 0.75)
    {
        $this->linkedListLength = $linkedListLength;
        $this->isExtends = $isExtends;
        $this->expansionFactor = $expansionFactor;
        $this->linkedCurrentPoint = 0;
        $this->currentLinkedListLength = 0;
    }

    public function getNodeIndex(int $index): DoubleLinkedListNode
    {
        // TODO: Implement getNodeIndex() method.
        return $this->linkedListArray[$index] ?? null;
    }

    public function add(DoubleLinkedListNode $node): bool
    {
        // TODO: Implement add() method.
        if ($this->isExtends === false) {
            if ($this->linkedCurrentPoint < $this->linkedListLength) {
                $this->linkedListArray[] = $node;
                if ($this->linkedCurrentPoint === 0) {
                    $node->setPrev(0);
                    $node->setNext(1);
                    return true;
                }
                $this->linkedCurrentPoint++;
                if ($this->linkedCurrentPoint === $this->linkedListLength -1) {
                    $node->setPrev($this->linkedCurrentPoint -1);
                    $node->setNext(null);
                } else {
                    $node->setPrev($this->linkedCurrentPoint - 1);
                    $node->setNext($this->linkedCurrentPoint + 1);
                }
                $this->currentLinkedListLength++;    
                return true;
            }
            return false;
        }
        if ($this->linkedCurrentPoint >= $this->linkedListLength * $this->expansionFactor) {
            $this->linkedListLength *= DoubleLinkedListInterface::ExpansionFactor;
            $newLinkedListArray = [];
            foreach ($this->linkedListArray as $nodeIndex => $nodeValue) {
                $newLinkedListArray[$nodeIndex] = $nodeValue;
            }
            $this->linkedListArray = $newLinkedListArray;
        }
        $this->linkedCurrentPoint++;
        $this->linkedListArray[] = $node;
        $node->setPrev($this->linkedCurrentPoint - 1);
        $node->setNext($this->linkedCurrentPoint + 1);
        return true;
    }

    public function del(DoubleLinkedListNode $node): bool
    {
        // TODO: Implement del() method.

    }

    public function rebuildLinkedList(): void
    {
        // TODO: Implement rebuildLinkedList() method.
    }

    public function search(DoubleLinkedListNode $node): int
    {
        // TODO: Implement search() method.
    }

    public function delByIndex(int $index): bool
    {
        // TODO: Implement delByIndex() method.
        if (isset($this->linkedListArray[$index])) {
            if ($index === 0) {
                unset($this->linkedListArray[$index]);
            }
        }
        return false;
    }
}