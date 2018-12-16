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
    public function getNodeIndex(int $index): DoubleLinkedListNode
    {
        // TODO: Implement getNodeIndex() method.
    }

    public function add(DoubleLinkedListNode $node): bool
    {
        // TODO: Implement add() method.
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
}