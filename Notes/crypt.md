### 加解密

加解密用于安全交换数据，分为对称与非对此加密

#### 对称加密

##### AES 加密

aes 采用分块加密，默认 32 字节，末尾会进行填充，常用填充模式为 pkcs5（末尾补 0），pkcs7（末尾为分块的默认字节减去当前字节的剩余字节），算法名中的数字为分块的 bit 位，aes-256-cbc（256 bit 的 iv 和密钥即分块长度为 32 位）

###### java 实现

```java
// java 自带 cipher 套件
public class CryptSecret {

    private static final String KEY = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa".substring(0, 32);

    private static final IvParameterSpec IV = new IvParameterSpec("0000000000000000".getBytes(StandardCharsets.UTF_8));

    public static String decrypt(String encrypted) {
        try {
            SecretKeySpec keySpec = new SecretKeySpec(KEY.getBytes(StandardCharsets.UTF_8), "AES");
            Cipher cipher = Cipher.getInstance("AES/CBC/PKCS5PADDING");
            cipher.init(Cipher.DECRYPT_MODE, keySpec, IV);
            byte[] original = cipher.doFinal(Base64.decodeBase64(encrypted));
            return new String(original);
        } catch (Exception ex) {
            ex.printStackTrace();
        }
        return null;
    }

    public static String encrypt(String value) {
        try {
            SecretKeySpec keySpec = new SecretKeySpec(KEY.getBytes(StandardCharsets.UTF_8), "AES");
            Cipher cipher = Cipher.getInstance("AES/CBC/PKCS5PADDING");
            cipher.init(Cipher.ENCRYPT_MODE, keySpec, IV);
            byte[] encrypted = cipher.doFinal(value.getBytes());
            return Base64.encodeBase64String(encrypted);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }
}
```

###### php 实现

```php
/**
 * php 使用 openssl 模块进行加密
 * options 默认为 0 使用 pkcs7 填充，返回加密后的 base64 字符串
 *         1，使用 pkcs7 填充，返回二进制字符串
 *         3，不填充，返回二进制字符串
 */
public function mcencrypt($input) {
    $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
    $input = $this->pkcs7($input, $size);
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, static::key, $iv);
    $data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $data = bin2hex($data);
    return $data;
}
private function pkcs7($data, $blockSize = 32)
{
    $pad = $blockSize - (strlen($data) % $blockSize);
    return $data . str_repeat(chr($pad), $pad);
}
```

