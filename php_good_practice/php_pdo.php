<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/8
 * Time: 12:53
 */
try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=bank;port=3306;charset=utf8',
        'root'
    );
    var_dump($pdo);
} catch (Exception $e) {
    echo $e->getMessage();
}

include 'pdo_config.php';

$pdo1 = new PDO(
    // 返回一个格式化字符串
    sprintf(
        'mysql:host=%s;dbname=%s;port=%s;charset=%s',
        $db_config['host'],
        $db_config['name'],
        $db_config['port'],
        $db_config['charset']
    ),
    $db_config['username'],
    $db_config['password']
);

var_dump($pdo1);
// PDO的预处理语句
$sql = 'select id from users where email = :email';
$statement = $pdo->prepare($sql);
$email = filter_input(INPUT_GET, 'email');
$statement->bindValue(':email', $email);
//指定预处理语句绑定数据类型
$sql1 = 'select * from users where id = :id';
$id = filter_input(INPUT_GET, 'id');
$statement1 = $pdo1->prepare($sql1);
$statement1->bindValue(':id', $id, PDO::PARAM_INT);
// 把预处理语句获取的结果当成关联数组处理
// 构建并执行SQL语句
$sql = 'SELECT id, email, FROM users WHERE email = :email or id = :id';
$statement = $pdo->prepare($sql);
$email = filter_input(INPUT_GET, 'email');
$id = filter_input(INPUT_GET, 'id');
$statement->bindValue('email', $email);
$statement->bindValue('id', $id, PDO::PARAM_INT);
$statement->execute();
// 迭代结果 fetch和fetchAll方法的参数为FETCH_ASSOC, 返回一个关联数组，数组的健是数据库的列名
//         参数为FETCH_NUM 返回一个键为数字的数组，数组的键是数据库列在查询结果中的索引
//          参数为FETCH_BOTH 返回一个即有键为列名又有键为数字的数组
//          参数FETCH_OBJ 返回一个对象，对象的属性是数据库的列名
while (($result = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
    echo $result['email'];
}
// 让预处理语句获取所有结果，把结果保存到关联数组中
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $item) {
    echo $result['email'];
}
// 让预处理语句获取一列，且一次获取一行，把结果保存到关联数组中
while (($email = $statement->fetchColumn(1)) !== false) {
    echo $email;
}
// 把预处理语句获取的行当成对象
while (($result = $statement->fetchObject()) !== false) {
    echo $result->name;
}
