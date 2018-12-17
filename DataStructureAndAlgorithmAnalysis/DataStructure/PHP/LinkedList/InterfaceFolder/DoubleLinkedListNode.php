<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 18:27
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder;


interface DoubleLinkedListNode extends LinkedListNode
{
    public function setPrev(int $prev): void ;

    public function getPrev(): int;

    public function setNext($next): void ;

    public function getNext(): int;
}