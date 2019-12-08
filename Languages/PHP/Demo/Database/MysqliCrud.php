<?php

class MysqliCurd
{
    /**
     * @return array
     * 预处理防止 sql 注入
     */
    public function action()
    {
        $mysqlLink = MysqliSingleton::getLink();
        $mysqlStatement = $mysqlLink->prepare('select id, user_id, created_at from ' . DB::weChatFansComparedTable . ' where id > ? limit ?, ?');
        if ($mysqlStatement) {
            $mysqlStatement->bind_param('iii', $id, $start, $end);
            $id = 10;
            $start = 1;
            $end = 10;
            $mysqlStatement->execute();
        } else {
            var_dump($mysqlLink->error);
        }
        $mysqlStatement->execute();
        $mysqlStatement->bind_result($id, $user_id, $created_at);
        $result = [];
        while ($mysqlStatement->fetch()) {
            $result[] = ['id' => $id, 'user_id' => $user_id, 'created_at' => $created_at];
        }
        $mysqlStatement->close();
        $mysqlLink->close();
        return $result;
    }
}