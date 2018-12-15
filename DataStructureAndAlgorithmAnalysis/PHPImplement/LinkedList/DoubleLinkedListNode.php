<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/15
 * Time: 16:39
 */

namespace DataStructureAndAlgorithmAnalysis\PHPImplement\LinkedListData;


class DoubleLinkedListNode extends LinkedListNode
{
    private $prev;

    private $next;

    public function __construct($data)
    {
        static::setData($data);
    }

    public function setPrev(int $prev): void
    {
        $this->prev = $prev;
    }

    public function setNext(int $next): void
    {
        $this->next = $next;
    }

    public function getPrev(): int
    {
        return $this->prev;
    }

    public function getNext(): int
    {
        return $this->next;
    }
}