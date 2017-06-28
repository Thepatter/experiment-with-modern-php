<?php

namespace experuse_interface\use_interface;
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/6/28
 * Time: 22:52
 * 使用接口
 */
/**
 * 从不同的源收集文本
 **/
use experuse_interface\use_interface\Documentable;

//require 'Documentable.php';

class DocumentStore
{
    protected $data = [];

    public function addDocument(Documentable $document)
    {
        $key = $document->getId();
        $value = $document->getContent();
        $this->data[$key] = $value;
    }

    public function getDocuments()
    {
        return $this->data;
    }
}