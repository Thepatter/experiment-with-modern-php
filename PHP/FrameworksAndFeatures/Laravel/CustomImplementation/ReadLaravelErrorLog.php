<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/17
 * Time: 18:14
 */

/**
 * laravel 错误日志查阅
 * Class ReadLaravelErrorLog
 */
class ReadLaravelErrorLog
{
    private $errorLogFilePath;

    private $logStream;

    public function __construct(string $errorLogFilePath = '')
    {

    }

    public function setLogPath(string $errorLogFilePath = '')
    {
        if (empty($errorLogFilePath)) {
            $errorLogFilePath = $_SERVER['DOCUMENT_ROOT'] . '\\' . 'storage\logs\\';
        }
        $this->errorLogFilePath = $errorLogFilePath;

        if (file_exists($this->errorLogFilePath)) {
            $LogsFiles = dir($this->errorLogFilePath);
            foreach ($LogsFiles as $file) {
                if ($file == 'laravel.log') {
                    $this->logStream = fopen('r', $errorLogFilePath . '\\' . $file);
                    break;
                }
            }
        }
    }
}