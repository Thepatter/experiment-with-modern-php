<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 17:57
 */
namespace DataStructureAndAlgorithmAnalysis\PHPImplement\LinkedList\InterfaceFolder;

use Iterator;
use DataStructureAndAlgorithmAnalysis\PHPImplement\LinkedList\InterfaceFolder\LinkedListNode;

interface LinkedList extends Iterator
{
    public function delByIndex(int $index): bool ;

    public function rebuildLinkedList(): void ;

    public function toArray(): array ;

    public function getLinkedListLength(): int;
}