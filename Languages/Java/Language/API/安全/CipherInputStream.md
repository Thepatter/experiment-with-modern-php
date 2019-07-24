*javax.crypto.CipherInputStream*

```java
// 构建一个输入流，以读取 in 中的数据，并使用指定的密码对数据进行加解密
CipherInputStream(InputStream in, Cipher cipher);
// 读取输入流中数据，该数据会被自动加解密
int read();
int read(byte[] b, int off, int len);
```