*javax.crypto.CipherOutputStream*

```java
// 构建一个输出流，以便将数据写入 `out`，并且使用指定的密码对数据进行加解密
CipherOutputStream(OutputStream out, Cipher cipher);
// 将数据写入输出流，该数据会被自动加解密
void write(int ch);
void write(byte[] b, int off, int len);
// 刷新密码缓冲区，如果需要的话，执行填充操作
void flush();
```