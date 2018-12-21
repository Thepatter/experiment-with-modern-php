<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/21
 * Time: 16:58
 */

namespace DataStructure\PHP\Algorithm;

use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\ImplementFolder\SingleLinkedList;
use DataStructureAndAlgorithmAnalysis\DataStructure\PHP\LinkedList\ImplementFolder\SingleLinkedListNode;

/**
 * 最近最少使用淘汰策略
 * Class LeastRecentlyUsed
 * @package DataStructure\PHP\Algorithm
 */
class LeastRecentlyUsed
{
    private $cacheArray = [];

    private $cacheArrayLength = [];

    private $cacheCurrentPoint = 0;

    private $cacheFile;

    public function __construct(int $cacheArrayLength)
    {
        $this->cacheArrayLength = $cacheArrayLength;
    }

    public function addDataCache(string $key, $data)
    {
        if ($this->cacheCurrentPoint >= $this->cacheArrayLength) {
            $this->leastRecentlyUsedClearCache();
        }
        $this->cacheArray[] = [$key => $data];
        return true;
    }

    public function getData(string $key)
    {
        foreach ($this->cacheArray as $key => $cache) {
            if (isset($cache[$key])) {
                $this->leastRecentlyUsedSort($key, $cache);
                return $cache[$key];
            }
        }
        return null;
    }

    public function getCacheArray()
    {
        return $this->cacheArray;
    }

    private function flushCache()
    {
        file_put_contents($this->cacheFile, serialize($this->cacheArray));
    }

    private function leastRecentlyUsedSort($key, $cache)
    {
        $this->cacheArray[$key++] = $cache;
        unset($this->cacheArray[$key]);
    }
    private function leastRecentlyUsedClearCache()
    {
        array_shift($this->cacheArray);
    }
}