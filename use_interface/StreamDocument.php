<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/6/28
 * Time: 23:38
 */

namespace experuse_interface\use_interface;

/**
 * Class StreamDocument
 * @package experuse_interface\use_interface
 * 从远程URL获取HTML
 * 这种方式能读取流资源
 */
class StreamDocument implements Documentable
{
    protected $resource;
    protected $buffer;

    public function __construct($resource, $buffer = 4096)
    {
        $this->resource = $resource;
        $this->buffer = $buffer;
    }

    public function getId()
    {
        // TODO: Implement getId() method.
        return 'resource-' . (int)$this->resource;

    }

    public function getContent()
    {
        // TODO: Implement getContent() method.
        $streamContent = '';
        /**
         * rewind - 倒回文件指针的位置
         * bool rewind (resource $handle)
         * 将handle的文件位置指针设为文件流的开头 (如果文件以附加("a"或者"a+")模式打开,写入文件的任何数据
         * 总是会被附加在后面, 不管文件指针的位置)
         * handle 文件指针必须合法, 并且指向由fopen()成功打开的文件
         * 成功时返回True, 失败时返回false
         */
        rewind($this->resource);  // 倒回文件指针位置
        /**
         * feof - 测试文件指针是否待了文件结束的位置
         * bool feof (resource $handle)
         * 测试文件指针是否到了文件结束的位置
         * handle 文件指针必须是有效的,必须指向由fopen()或fsockopen()成功打开的文件(且还未由fclose()关闭)
         * 如果文件指针到了EOF或者出错则返回TRUE,否则返回一个错误(包括socket超时),其它情况则返回false
         */
        while (feof($this->resource) === false) {
            $streamContent .= fread($this->resource, $this->buffer);
        }

        return $streamContent;
    }

}