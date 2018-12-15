<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 18:29
 */

namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder;


interface DoubleLinkedList extends LinkedList 
{
    public function add(DoubleLinkedListNode $node): bool ;

    public function del(DoubleLinkedListNode $node): bool ;

    public function getNodeIndex(int $index): DoubleLinkedListNode;

    public function search(DoubleLinkedListNode $node): int ;
}