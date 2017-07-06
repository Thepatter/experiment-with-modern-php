<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/6
 * Time: 22:21
 */
//注册用户脚本
try {
    // 验证电子邮件地址
    /**
     * filter_input - 通过名称获取特定的外部变量，并且可以通过过滤器处理它
     * mixed filter_input(int $type, string $variable_name [, int $filter = FILTER_DEFAULT [, mixed $options]])
     * params type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER或INPUT_ENV之一
     * variable_name 待获取戴的变量名
     * filter 验证标记
     * options 一个选项的关联数组，或者按区分的标示，如果过滤器接受选项，可以通过数组的"flags"位区提供这些标示
     * return 成功返回所请求的变量，失败返回false，如果variable_name不存在的话则返回NULL。如果标示FILTER_NULL_ON_FAILURE
     * 被使用了，那么当变量不存时返回false，当过滤失败时返回null。
     */
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new Exception('Invalid email');
    }
    // 验证密码
    $password = filter_input(INPUT_POST, 'password');
    if (!$password || mb_strlen($password) < 8) {
        throw new Exception('Password must contain 8+ characters');
    }
    // 穿件密码的哈希值
    /**
     * password_hash - 创建密码的哈希
     * string password_hash(string $password, integer $algo [, array $options])
     * params  password 用户的密码
     *         algo 一个用在散列密码时指示算法的密码算法常量 PASSWORD_DEFAULT 使用bcrypt算法，PASSWORD_BCRYPT,使用CRYPT_BLOWFISH
     *              算法创建哈希。产生兼容使用"$2y$"的crypt()，结果将会是60个字符的字符串，失败是返回false
     *         options 一个包含有选项的关联数组。目前支持两个选项：salt，在散列密码时加的盐，以及cost，用了指明算法递归的层数。省略后使用
     *                 随机盐与默认cost 7.0已经移除盐值，cost默认10
     * return 返回哈希后的密码，失败返回false
     *
     */
    $passwordHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    if ($passwordHash === false) {
        throw new Exception('password hash failed');
    }
    // 创建用户账户
    $user = new User();
    $user->email = $email;
    $user->password_hash = $passwordHash;
    $user->save();
    //重定向到登录页面
    header('HTTP/1.1 302 Redirect');
    header('Location: /login.php');
} catch (Exception $e) {
    // 报告错误
    header('HTTP/1.1 400 Bad request');
    echo $e->getMessage();
}
