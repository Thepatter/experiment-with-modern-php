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
            $tempFileHandle = fopen($file, 'r');
            if ($tempFileHandle !== false) {
                while ($csvLine = fgetcsv($tempFileHandle, 1024, ',')) {
                    $tempLineArray = [];
                    foreach ($csvLine as $item) {
                        $tempLineArray[] = trim(iconv('gb2312', 'utf-8', $item));
                    }
                    $csvArray[] = $tempLineArray;
                }
            }
            fclose($tempFileHandle);
        }
        return $csvArray;
    }
}