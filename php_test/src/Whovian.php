<?php

/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 7/18/2017
 * Time: 10:51 PM
 */
class Whovian
{
    /**
     * @var string
     */
    protected $favoriteDoctor;

    /**
     * Constructor
     * @param string $favoriteDoctor
     */
    public function __construct($favoriteDoctor)
    {
        $this->favoriteDoctor = (string)$favoriteDoctor;
    }
    /**
     * say
     * @return string
     */
    public function say()
    {
        return 'The best doctor is ' . $this->favoriteDoctor;
    }
    /**
     * Respond to
     * @param string $input
     * @return string
     * @throws \Exception
     */
    public function respondTo($input)
    {
        $input = strtolower($input);
        $myDoctor = strtolower($this->favoriteDoctor);
        /**
         * strpos - 查找字符串首次出现的位置
         * mixed strpos(string $haystack, mixed $needle [, int $offset = 0])
         * 返回needle 在 haystack 中首次出现的数字位置
         * params haystack 在该字符串中进行查找
         *        needle   如果needle不是一个字符串,那么它将被转换为整形并被视为字符的顺序值
         *        offset   如果提供了此参数,搜索会从字符串该字符数的起始位置开始统计.如果是负数,搜索会从字符串结尾指定字符数开始
         * return 返回needle存在haystack字符串起始的位置.字符串位置从0开始,而不是从1开始.
         */
        if (strpos($input, $myDoctor) === false) {
            throw new Exception(
                sprintf(
                    'No way! %s is the best doctor ever!',
                    $this->favoriteDoctor
                )
            );
        }
        return 'I agree!';
    }
}
