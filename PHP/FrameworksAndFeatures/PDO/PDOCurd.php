<?php
/**
 * Created by PhpStorm.
 * User: company
 * Date: 2018/11/24
 * Time: 15:34
 */

class PDOCurd
{
    public function insert()
    {
        $sql = 'insert into ' . DB::weChatFansComparedTable .
            '(`id`, `user_id`, `compared_key`, `state`, `accounts`, `another_accounts`, `compared_result`) values(:id, :user_id, :compared_key, :state, :accounts, :another_accounts, :compared_result)';
        $pdoStatement = PDOMysqlSingleton::prepare($sql);
        for ($i = 0; $i < 25; $i++) {
            $pdoStatement->execute([
                ':id' => null,
                ':user_id' => 999,
                ':compared_key' => hash('sha256', rand(100000000, 999999999)),
                ':state' => 'ready',
                ':accounts' => json_encode([
                    'head_img' => 'authorization_info_accounts_info_head_img',
                    'nick_name' => 'authorization_info_accounts_info_nick_name',
                ]),
                ':another_accounts' => json_encode([
                    'head_img' => 'authorization_info_accounts_info_head_img',
                    'nick_name' => 'authorization_info_accounts_info_nick_name',
                ]),
                ':compared_result' => json_encode([
                    'repeatFans' => 10000,
                    'setRatio' => [
                        'man' => 15.67,
                        'woman' => 33.33,
                        'normal' => 51.00,
                    ],
                    'newFans' => 310021,
                    'fans' => [
                        'accountsA' => 4500131,
                        'accountsB' => 3242312,
                    ]
                ])
            ]);
        }
        exit;
    }

    public function select()
    {
        $userId = 999;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 10;
        $sql = 'select * from ' . DB::weChatFansComparedTable . ' where `user_id` = :user_id order by id desc limit ' . ($page - 1) * $perPage . ',' . $page * $perPage;
        $pdoStatement = PDOMysqlSingleton::prepare($sql);
        $pdoStatement->execute([
            'user_id' => $userId,
        ]);
        $result = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            var_dump($pdoStatement->errorInfo());
        }
        $userAccountsFansComparedResult = [];
        foreach ($result as $value) {
            $userAccountsFansComparedResult[] = [
                'id' => $value['id'],
                'user_id' => $value['user_id'],
                'compared_key' => $value['compared_key'],
                'state' => ['ready' => '准备', 'begin' => '进行中', 'complete' => '已完成' ][$value['state']],
                'accounts' => json_decode($value['accounts']),
                'another_accounts' => json_decode($value['another_accounts']),
                'compared_result' => json_decode($value['compared_result']),
                'created_at' => $value['created_at'],
                'updated_at' => $value['updated_at'],
            ];
        }
        return Response::resJson($userAccountsFansComparedResult);
    }

    public function update()
    {
        $sql = 'update ' . DB::weChatFansComparedTable . ' set `compared_result` = :compared_result, `state` = :state where compared_key =:compared_key';
        $pdo = PDOMysqlSingleton::prepare($sql);
        $result = $pdo->execute([
            'compared_result' => json_encode([
                'repeatFans' => 100001,
                'setRatio' => [
                    'man' => 15.67,
                    'woman' => 33.33,
                    'normal' => 51.00,
                ],
                'newFans' => 3100211,
                'fans' => [
                    'accountsA' => 42500131,
                    'accountsB' => 32142312,
                ]
            ]),
            'state' => 'complete',
            'compared_key' => '4a2e5a6adfd079360692a12780afb3ae69b7cf0136e94ee70713ee0eea6c29a6',
        ]);
        var_dump($result);exit;
    }
}