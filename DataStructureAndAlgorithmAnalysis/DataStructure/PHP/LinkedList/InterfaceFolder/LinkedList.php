<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 17:57
 */
namespace DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\InterfaceFolder;

use Iterator;

interface LinkedList extends Iterator
{
    const ExpansionFactor = 2;

    public function delByIndex(int $index): bool ;

    public function rebuildLinkedList(): void ;

    public function toArray(): array ;

    public function getLinkedListLength(): int;
}