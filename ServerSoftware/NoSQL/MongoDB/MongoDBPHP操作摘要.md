## MongoDB PHP 操作摘要

官网地址: https://docs.mongodb.com/php-library/master/tutorial/install-php-library/

### 环境

* 安装 `php-mongodb` 扩展
* 安装`composer require mongodb/mongodb`

### CURD 操作

* insert __如果插入文档的 `_id` 值在集合中不是唯一的,会插入失败__

```php
$collection = (new \MongoDB\Client)->test->users;
// 插入单个文档,返回一个 MongoDB\InsertOneResult 对象
$insertOneResult = $collection->insertOne([
    'username' => 'admin',
    'email' => 'admin@example.com',
    'name' => 'Admin User'
])
// 插入多个文档,返回一个 MongoDB\InsertManyResult 对象
$insertManyResult = $collection->insertMany([
    [
        'username' => 'test',
        'email' => 'test@example.com',
        'name' => 'Test User',
    ],
    [
        'username' => 'YaoWen',
        'email' => 'yaowen@example.com',
        'name' => 'Yao Wen'
    ]
]);
```

* 查询

```
// 查找单个,返回一个 MongoDB\Model\BSONDocument 对象或 null
$collection = (new \MongoDB\Client)->test->users;
$document = $collection->findOne(['username' => 'YaoWem']);
// 查找多个, 返回 MongoDB\Driver\Cursor 对象
$cursor = $collection->find(
	[
        'username' => 'YaoWen',
	],
	[
		// 限制返回的字段
        'projection' => [
            'name' => 1,
            'borough' => 1
        ],
        // 限制返回的条数
        'limit' => 4,
	]
)
```

* 更新

```php
$collection->insertOne(['name' => 'Bob', 'state' => 'ny'])
// 更新单个
$collection->updateOne([
    ['state' => 'ny'],	// 查询条件
    ['$set' => ['country' => 'us']]  // 修改器
])
// 更新多个
$updateResult = $collection->updateMany(
    ['state' => 'ny'],
    ['$set' => ['country' => 'us']]
);
```

* 替换文件,替换操作不是更新文档以包括新字段或新字段值,而是用新文档替换整个文档,但保留原始文档的 `_id `值

```php
$collection->insertOne(['name' => 'Bob', 'state' => 'ny']);
$updateResult = $collection->replaceOne(
    ['name' => 'Bob'],
    ['name' => 'Robert', 'state' => 'ca']
);

printf("Matched %d document(s)\n", $updateResult->getMatchedCount());
printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
```

* upsert .没有文档与过滤器匹配,则操作将创建一个新文档并将其插入,如果有匹配的文档,操作修改或替换匹配的文件或文档

```php
$updateResult = $collection->updateOne(
    ['name' => 'Bob'],
    ['$set' => ['state' => 'ny']],
    ['upsert' => true]
);

printf("Matched %d document(s)\n", $updateResult->getMatchedCount());
printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
printf("Upserted %d document(s)\n", $updateResult->getUpsertedCount());

$upsertedDocument = $collection->findOne([
    '_id' => $updateResult->getUpsertedId(),
]);

var_dump($upsertedDocument);
```

* 删除一个文档

```php
$collection->insertOne(['name' => 'Bob', 'state' => 'ny']);
$collection->insertOne(['name' => 'Alice', 'state' => 'ny']);
// 删除单个
$deleteResult = $collection->deleteOne(['state' => 'ny']);
// 删除多个
$deleteResult = $collection->deleteMany(['state' => 'ny']);
```

### 导入数据集

```php
<?php

$filename = 'https://media.mongodb.org/zips.json';
$lines = file($filename, FILE_IGNORE_NEW_LINES);

$bulk = new MongoDB\Driver\BulkWrite;

foreach ($lines as $line) {
    $bson = MongoDB\BSON\fromJSON($line);
    $document = MongoDB\BSON\toPHP($bson);
    $bulk->insert($document);
}

$manager = new MongoDB\Driver\Manager('mongodb://127.0.0.1/');

$result = $manager->executeBulkWrite('test.zips', $bulk);
printf("Inserted %d documents\n", $result->getInsertedCount());
```

### 聚合

```php
<?php

$collection = (new MongoDB\Client)->test->recipes;

$cursor = $collection->aggregate(
    [
        ['$group' => ['_id' => '$first_name', 'name_count' => ['$sum' => 1]]],
        ['$sort' => ['_id' => 1]],
    ],
    [
        'collation' => ['locale' => 'de@collation=phonebook'],
    ]
);
```

