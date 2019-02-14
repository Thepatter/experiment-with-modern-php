## javax.crypto.Cipher

* `static Cipher getInstance(String algorithmName)`

* `static Cipher getInstance(String algorithmName, String providerName)`

  返回实现了指定加密算法的 `Cipher` 对象。如果未提供该算法，则抛出一个 `NoSuchAlgorithmException` 异常

* `int getBlockSize()`

  返回密码块的大小，如果该密码块不是一个分组密码，则返回 0

* `int getOutputSize(int inputLength)`

  如果下一个输入数据块拥有给定的字节数，则返回所需的输出缓冲区大小。本方法的运行要考虑到密码对象中所有已缓冲的字节数量

* `void init(int mode, Key key)`

  对加密算法对象进行初始化。`Mode` 是 `ENCRYPT_MODE`，`DECRYPT_MODE`，`WARP_MODE`，`UNWARP_MODE`

* `byte[] update(byte[] in)`

* `byte[] update(byte[] in, int offset, int length)`

* `int update(byte[] in, int offset, int length, byte[] out)`

  对输入数据块进行转换。前两个方法返回输出，第三个方法返回放入 `out` 的字节数

* `byte[] doFinal()`

* `byte[] doFinal(byte[] in)`

* `byte[] doFinal(byte[] in, int offset, int length)`

* `int doFinal(byte[] in, int offset, int length, byte[] out)`

  转换输入的最后一个数据块，并刷新该加密算法对象的缓冲。前三个方法返回输出，第四个方法返回放入 `out` 的字节数

  