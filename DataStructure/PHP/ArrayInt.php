<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2019/3/25
 * Time: 16:56
 */

class ArrayInt
{
    private $length = 10;

    private $data = [];

    private $currentLength = 0;

    public function __construct(int $length)
    {
        if ($length >= 0) {
            $this->length = $length;
        }
    }

    public function add(int $value)
    {
        if ($this->currentLength >= $this->length) {
            return -1;
        }
        $this->data[$this->currentLength] = $value;
        ++$this->currentLength;
        return $this->currentLength - 1;
    }

    public function del(int $index)
    {
        if ($index < 0 || $index > $this->length) {
            return false;
        }
        unset($this->data[$index]);
        --$this->currentLength;
        return true;
    }

    public function put(int $index, int $value)
    {
        if ($index < 0 || $index > $this->length) {
            return -1;
        }
        $old = $this->data[$index];
        $this->data[$index] = $value;
        return $old;
    }

    public function get(int $index)
    {
        if ($index < 0 || $index >= $this->currentLength) {
            throw new Exception('数组越界');
        }
        return $this->data[$index];
    }

    public function bubbleSort(): void
    {
        for ($i = 0; $i < $this->currentLength; $i++) {
            $exchange = false;
            for ($j = $i + 1; $j < $this->currentLength; $j++) {
                if ($this->data[$i] > $this->data[$j]) {
                    $tmp = $this->data[$i];
                    $this->data[$i] = $this->data[$j];
                    $this->data[$j] = $tmp;
                    $exchange = true;
                }
            }
            if (!$exchange) {
                break;
            }
        }
    }

    public function binarySearch(int $value)
    {
        $start = 0;
        $end = $this->currentLength - 1;
        while ($start <= $end) {
            $mid = (int) (($end - $start) / 2 + $start);
            if ($this->data[$mid] > $value) {
                $end = $mid - 1;
            } elseif ($this->data[$mid] < $value) {
                $start = $mid + 1;
            } else {
                return $mid;
            }
        }
        return -1;
    }

    public function each()
    {
        for ($i = 0; $i < $this->currentLength; $i++) {
            yield $this->data[$i];
        }
    }

    public static function main()
    {
        $arrayInt = new ArrayInt(200);
        $sourceArray = array_slice(range(11, 112433, 33), 0, 200);
        shuffle($sourceArray);
        foreach ($sourceArray as $item) {
            $arrayInt->add($item);
        }
        $arrayInt->bubbleSort();
        foreach ($arrayInt->each() as $it) {
            echo $it . PHP_EOL;
        }
        print_r($arrayInt->get(33));
        print_r($arrayInt->binarySearch(1100));
    }
}

ArrayInt::main();