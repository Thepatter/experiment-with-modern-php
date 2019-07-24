*javax.crypto.Cipher*

```java
// 返回实现了指定加密算法的 Ciper 对象，未提供该算法则抛出 NoSuchAlgorithmException
static Cipher getInstance(String algorithmName);
static Cipher getInstance(String algorithnName, String providerName);
// 返回密码块的大小，如果该密码块不是一个分组密码，则返回 0
int getBlockSize();
// 如果下一个输入数据块拥有给定的字节数，则返回所需的输出缓冲区大小。运行要考虑密码对象中已缓冲的字节数量
int getOutputSize(int inputLength);
// 对加密算法对象进行初始化。Mode 为 ENCRYPT_MODE, DECRYPT_MODE, WARP_MODE, UMWAP_MODE
void init(int mode, Key key);
// 对输入数据块进行转换。返回输出
byte[] update(byte[] in);
byte[] update(byte[] in, int offset, int length);
// 对输入数据块进行转换，返回 out 的字符数
int update(byte[] in, int offset, int length, byte[] out);
// 转换输入的最后一个数据块，并刷新该加密算法对象的缓冲
byte[] doFinal();
byte[] doFinal(byte[] in);
// 转换输入的最后一个数据块，返回输出
byte[] deFinal(byte[] in, int offset, int length);
// 转换输入的最后一个数据块，返回 out的字节数
int doFinal(byte[] in, int offset, int length, byte[] out);
```