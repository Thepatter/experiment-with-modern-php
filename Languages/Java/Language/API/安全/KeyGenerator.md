## javax.crypto.KeyGenerator

* `static KeyGenerator getInstance(String algorithmName)`

  返回实现指定加密算法的 `keyGenerator` 对象。如果未提供该加密算法，则抛出一个 `NoSuchAlgorithmException` 异常

* `void init(SecureRandom random)`

* `void init(int keySize, SecureRandom random)`

  对密钥生成器进行初始化

* `SecretKey generateKey()`

  生成一个新的密钥