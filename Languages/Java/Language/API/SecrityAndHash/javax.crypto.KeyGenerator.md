*javax.crypto.KeyGenerator*

```java
// 返回实现指定加密算法的 KeyGenerator 对象。未提供该算法，抛出 NoSuchAlgorithmException 异常
static KeyGenerator getInstance(String algorithmName);
// 密钥生成器初始化
void init(SecureRandom random);
void init(int keySize, SecureRandom random);
// 生成密钥
SecretKey generateKey();
```

