<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/6/29
 * Time: 0:07
 */

namespace experuse_interface\use_interface;

use experuse_interface\use_interface\DocumentStore as DocumentStore;
use experuse_interface\use_interface\HtmlDocument as HtmlDocument;
use experuse_interface\use_interface\CommandOutputDocument as CommandOutputDocument;
use experuse_interface\use_interface\StreamDocument as StreamDocument;

require 'Documentable.php';
require 'DocumentStore.php';
require 'HtmlDocument.php';
require 'CommandOutputDocument.php';
require 'StreamDocument.php';

$documentStore = new DocumentStore();

// 添加HTML文档
$htmlDoc = new HtmlDocument('http://php.net');
$documentStore->addDocument($htmlDoc);

// 添加流文档
$streamDoc = new StreamDocument(fopen('stream.txt', 'rb'));
$documentStore->addDocument($streamDoc);

// 添加终端命令文档
$cmdDoc = new CommandOutputDocument('cat /etc/hosts');
$documentStore->addDocument($cmdDoc);

print_r($documentStore->getDocuments());
