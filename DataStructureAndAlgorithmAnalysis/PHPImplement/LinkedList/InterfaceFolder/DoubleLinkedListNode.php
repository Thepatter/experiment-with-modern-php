<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 18:27
 */

namespace DataStructureAndAlgorithmAnalysis\PHPImplement\LinkedList\InterfaceFolder;


interface DoubleLinkedListNode extends LinkedListNode
{
    public function setPrev(int $prev): void ;

    public function getPrev(): int;
}