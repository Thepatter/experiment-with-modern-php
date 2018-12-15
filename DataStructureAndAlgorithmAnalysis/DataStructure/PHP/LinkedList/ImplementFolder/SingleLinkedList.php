<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:35
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\ImplementFolder;


class SingleLinkedList
{
    public function add($node): bool
    {
        // TODO: Implement add() method.
//        if (static::valid() && static::checkNode($node)) {
//            $this->linkedListArray[$this->current()] = $node;
//            $node->set
//        }
        return false;
    }

    public function del($node): bool
    {
        // TODO: Implement del() method.
    }

    public function delByIndex(int $index): bool
    {
        // TODO: Implement delByIndex() method.
    }

    public function rebuildLinkedList(): void
    {
        // TODO: Implement rebuildLinkedList() method.
    }

    public function getNode(int $index): LinkedListNode
    {
        // TODO: Implement getNode() method.
    }

    public function search($node): int
    {
        // TODO: Implement search() method.
    }

    private function checkNode(LinkedListNode $node): bool
    {
        return $node instanceof SingleLinkedListNode;
    }
}