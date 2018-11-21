<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/11/19
 * Time: 13:43
 */

class CSVToArray
{
    public function csv2Array(string $file)
    {
        $csvArray = [];
        if (file_exists($file)) {
            // 替换成协程读取文件
            $readCSVFile = function ($fileHandle) {
                if ($fileHandle !== false) {
                    while ($csvLine = fgetcsv($fileHandle, 1024, ',')) {
                        yield $csvLine;
                    }
                }
            };
            $tempFileHandle = fopen($file, 'r');
            foreach ($readCSVFile($tempFileHandle) as $csvLine) {
                $tempLineArray = [];
                foreach ($csvLine as $item) {
                    $tempLineArray[] = trim(iconv('gb2312', 'utf-8', $item));
                }
                $csvArray[] = $tempLineArray;
            }
            fclose($tempFileHandle);
        }
        return $csvArray;
    }
}