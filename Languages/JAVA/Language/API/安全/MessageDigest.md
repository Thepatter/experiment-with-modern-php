## java.security.MessageDigest

* `static MessageDigest getInstance(String algorithmName)`

  返回实现指定算法的 `MessageDigest` 对象。如果没有提供该算法，则抛出一个 `NoSuchAlgorithmException` 异常

* `void update(byte intput)`

* `void update(byte[] input)`

* `void update(byte[] input, int offset, int len)`

  使用指定的字节来更新摘要

* `byte[] digest()`

  完成散列计算，返回计算所得的摘要，并复位算法对象

* `void reset()`

  重置摘要