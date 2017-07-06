<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/6
 * Time: 23:19
 */
//登录用户脚本
session_start();
try {
    // 从请求主体中获取用户信息
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    // 使用电子邮件从数据库查找
    $user = User::findByEmail($email);
    // 验证密码和账户的密码韩系值是否匹配
    /**
     * password_verify - 验证密码是否和哈希匹配
     * boolean password_verify(string $password, string $hash)
     * params  password 用户的密码， hash 一个由password_hash()创建的散列值
     * return  匹配返回true， 失败返回false
     */
    if (password_verify($password, $user->password_hash) === false) {
        throw new Exception('Invalid password');
    }
    // 如果需要，重新计算密码的哈希值
    $currentHashAlgorithm = PASSWORD_DEFAULT;
    $currentHashOptions = array('cost' => 15);
    /**
     * password_needs_rehash - 检查给定哈希是否与给定选项匹配
     * boolean password_needs_rehash(sting $hash, integer $algo [, array $options])
     * params   hash 一个由password_hash()创建的散列值
     *          algo 一个用来在散列密码时指示算法的密码算法常量
     *          options  一个包含由选项的关联数组，salt,盐值，cost，算法递归层数。
     * return   如果要重新匹配哈希值以给匹配给定的算法和选项，则返回true，否则返回false
     */
    $passwordNeedRehash = password_needs_rehash(
        $password,
        $currentHashAlgorithm,
        $currentHashOptions
    );
    if ($passwordNeedRehash === true) {
        // 保存新计算得到的密码哈希
        $user->password_hash = password_hash(
            $password,
            $currentHashAlgorithm,
            $passwordNeedRehash
        );
        $user->save();
    }
    // 保存登录状态到会话中
    $_SESSION['user_logged_in'] = 'yes';
    $_SESSION['user_email'] = $email;
    // 重定向到个人资料页面
    header('HTTP/1.1 302 Redirect');
    header('Location: /user-profile.php');
} catch (Exception $e) {
    header('HTTP/1.1 401 Unauthorized');
    echo $e->getMessage();
}