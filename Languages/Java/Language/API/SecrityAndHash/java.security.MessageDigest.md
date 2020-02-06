#### *java.security.MessageDigest*

```java
// 返回指定算法的 MessageDigest 对象，如果没有提供该算法，抛出 NoSuchAlgorithmException 异常
static MessageDigest getInstance(String algorithmName);
// 使用指定的字节来更新摘要
void update(byte input);
void update(byte[] input);
void update(byte[] input, int offset, int len);
// 返回散列摘要，并复位算法对象
byte[] digest();
// 重置摘要
void reset();
```

