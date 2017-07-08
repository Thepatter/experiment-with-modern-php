<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/8
 * Time: 21:58
 */
include 'pdo_config.php';

try {
    $pdo = new PDO(
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
} catch (Exception $e) {
    die('Database connect failed' . $e->getMessage());
}

// 查询语句
$stmtSubtract = $pdo->prepare('UPDATE ims_sz_yi_member set virtual_currency = virtual_currency - :amount where id = :id');
$stmtAdd = $pdo->prepare('UPDATE ims_sz_yi_member SET virtual_currency = virtual_currency + :amount where id = :id');
//开始事务
$pdo->beginTransaction();
// 从账户1中取钱
$fromAccount = '260';
$withdrawal = 2000;
$stmtSubtract->bindValue(':id', $fromAccount, PDO::PARAM_INT);
$stmtSubtract->bindValue(':amount', $withdrawal, PDO::PARAM_INT);
$stmtSubtract->execute();
// 把钱存入账户2
$toAccount = '261';
$deposit = 2000;
$stmtAdd->bindValue(':id', $toAccount, PDO::PARAM_INT);
$stmtAdd->bindValue(':amount', $deposit, PDO::PARAM_INT);
$stmtAdd->execute();
// 提交事务
$pdo->commit();

$sql = 'SELECT id, realname, virtual_currency from ims_sz_yi_member where id = :id';
$statement = $pdo->prepare($sql);
$statement->bindValue(':id', $fromAccount);
$statement->execute();
while (($result = $statement->fetch(PDO::FETCH_OBJ)) !== false) {
    echo 'ID 为'. $result->id. '的'. $result->realname. '转了'. $withdrawal. '到'. $toAccount. '账户'. '，当前余额为'.$result->virtual_currency;
    //var_dump($result);
}
