<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:35
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\ImplementFolder;


use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\AbstractClassFolder\LinkedList;
use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder\SingleLinkedList as SingleLinkedListInterface;
use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder\SingleLinkedListNode;

class SingleLinkedList extends LinkedList implements SingleLinkedListInterface
{
    private $isExtends;

    private $expansionFactor;

    public function __construct(int $linkedListLength = 10, bool $isExtends = false, float $expansionFactor = 0.75)
    {
        $this->linkedListLength = $linkedListLength;
        $this->isExtends = $isExtends;
        $this->expansionFactor = $expansionFactor;
    }

    public function rebuildLinkedList(): void
    {
        // TODO: Implement rebuildLinkedList() method.
    }

    public function del(SingleLinkedListNode $node): bool
    {
        // TODO: Implement del() method.
        if (empty($this->linkedListArray)) {
            return false;
        }

    }

    public function delByIndex(int $index): bool
    {
        // TODO: Implement delByIndex() method.
        if (isset($this->linkedListArray[$index])) {
            if ($this->linkedListArray[$index]->getNext() === 1) {
                $this->linkedListArray[$index]->setNext($index + 1);
            } else if (is_null($this->linkedListArray[$index]->getNext())) {
                $this->linkedListArray[$index -1]->setNext(null);
            } else {
                $this->linkedListArray[$index -1]->setNext($index + 1);
            }
            unset($this->linkedListArray[$index]);
            $this->linkedListLength--;
            return true;
        }
        return false;
    }

    public function add(SingleLinkedListNode $node): bool
    {
        // TODO: Implement add() method.
        if ($this->isExtends === false) {
            if ($this->linkedCurrentPoint < $this->linkedListLength) {
                $this->linkedListArray[] = $node;
                $node->setNext($this->linkedCurrentPoint + 1);
                if ($this->linkedCurrentPoint === $this->linkedListLength - 1) {
                    $node->setNext(null);
                }
                $this->linkedCurrentPoint++;
                return true;
            }
            return false;
        }
        if ($this->linkedCurrentPoint <= $this->linkedListLength * $this->expansionFactor) {
            $this->linkedListLength *= 2;
            $newLinkedListArray = [];
            foreach ($this->linkedListArray as $keyIndex => $nodeValue) {
                $newLinkedListArray[$keyIndex] = $nodeValue;
            }
            $this->linkedListArray = $newLinkedListArray;
        }
        $this->linkedCurrentPoint++;
        $this->linkedListArray[$this->linkedCurrentPoint] = $node;
        return true;
    }

    public function search(SingleLinkedListNode $node): int
    {
        // TODO: Implement search() method.

    }

    public function getNodeIndex(int $index): SingleLinkedListNode
    {
        // TODO: Implement getNodeIndex() method.
        return $this->linkedListArray[$index] ?? null;
    }

}