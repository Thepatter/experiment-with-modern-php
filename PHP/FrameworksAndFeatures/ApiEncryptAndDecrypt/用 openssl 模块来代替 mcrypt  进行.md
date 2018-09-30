## 用 `openssl` 模块来代替 `mcrypt`  进行

### `openssl` 加密

`openssl_encrypt($data, $method, $password, $options, $iv)`

* `$data` 要加密的数据
* `$method` 要加密的方式, `openssl_get_cipher_methods([bool $aliases = false ])`
* `$password` 密钥串
* `$options` 选项`0` ,`OPENSSL_RAW_DATA = 1` , `OPENSSL_ZERO_PADDING = 2`, `OPENSSL_NO_PADDING = 3`
* `$iv` 向量

#### `options = 0` `method = AES-128-CBC`

返回一个 `base64` 加密串

默认选项下,加密结果直接返回 base64 加密串

默认选项下会自动使用 `PKCS7` 进行填充

#### `options = 2`

返回 false

`openssl` 不推荐补 `0` 的方式,就算使用该选项也不会自动进行 `padding`, 需要手动进行 `padding`

返回结果已经是 base64 字符串

如果进行手动补零则与 `mcrypt` `pkcs7`手动补零加密结果一致

```php
public function padZero($data, $blocksize = 16)
{
    $pad = $blocksize - (strlen($data) % $blocksize);
    return $data . str_repeat("\0", $pad);
}
public function unpadZero($data)
{
    return rtrim($data, "\0");
}
```

#### `options = 3`

`openssl` 不支持 `no padding` ,如果使用该选择,则必须手动进行 `padding`

手动进行填充之后返回结果默认没有进行 `base64` 编码

#### `options = 1`

返回了加密结果,但是没有进行 `base64` 编码,会自动进行 `pkcs7` 方式的填充, 默认的 `padding`不是补零

#### `openssl` 与 `mcrypt_encrypt` 模块区别

* `openssl` 都必须进行 `padding`
* `mcrypt_encrypt` 默认采用补零的方式
* `MCRYPT_RIJNDAEL_128` 对应于 `openssl` 的加密方式为 `aes-256-cbs` 
* 如果使用`mcrypt_encrypt`  使用  `MCRYPT_RIJNDAEL_128` ,两种方式都对加密字符串进行相同的 `padding` ,密钥是 16 位时,`openssl` 使用 `aes-128-cbc` , 密钥是 32 位时 `openssl` 使用 `aes-256-cbc` 就可以一致

