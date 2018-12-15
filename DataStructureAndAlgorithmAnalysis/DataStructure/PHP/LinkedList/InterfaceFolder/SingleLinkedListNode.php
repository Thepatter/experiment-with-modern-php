<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 18:16
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder;


interface SingleLinkedListNode extends LinkedListNode
{
    public function setNext(int $next): void ;

    public function getNext(): int ;
}