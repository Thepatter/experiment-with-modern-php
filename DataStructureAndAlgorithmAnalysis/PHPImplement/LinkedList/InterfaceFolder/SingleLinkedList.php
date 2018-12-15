<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 18:15
 */

namespace DataStructureAndAlgorithmAnalysis\PHPImplement\LinkedList\InterfaceFolder;


interface SingleLinkedList extends LinkedList
{
    public function add(SingleLinkedListNode $node): bool ;

    public function del(SingleLinkedListNode $node): bool ;

    public function getNodeIndex(int $index): SingleLinkedListNode;

    public function search(SingleLinkedListNode $node): int ;

}