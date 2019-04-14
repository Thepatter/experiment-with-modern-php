## javax.crypto.CipherInputStream

* `CipherInputStream(InputStream in, Cipher cipher)`

  构建一个输入流，以读取 `in` 中的数据，并且使用指定的密码对数据进行解密和加密

* `int read()`

* `int read(byte[] b, int off, int len)`

  读取输入流中数据，该数据会被自动解密和加密