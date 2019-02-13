<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2019/2/13
 * Time: 14:50
 */

$messageDigest =  hash('sha1', file_get_contents("./input.txt"));

$messageDigestLength = strlen($messageDigest);

$messageDigestArray = str_split($messageDigest, 2);

foreach ($messageDigestArray as $value) {
    echo strtoupper($value) . " ";
}

foreach(openssl_get_cipher_methods() as $cipher_method) {
    echo $cipher_method . PHP_EOL;
}