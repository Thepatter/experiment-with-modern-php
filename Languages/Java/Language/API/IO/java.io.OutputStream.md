*java.io.OutputStream*
```java
// * 抽象类 `OutputStream` 是写入二进制数据的根类

abstract void write(int b);

// 将指定的字节写入到该输出流中。参数 b 是一个 int 值（byte) b 写入到输出流中

void write(byte[] b);

// 将数组 b 中的所有字节写出到输入流中

void write(byte[] b, int off, int len);

// 将 b[off], b[off+1], b[off+len-1] 写入到输出流中

void close();

// 关闭该输出流，并且释放其占用的任何资源

void flush();

// 清掉输出流，强制写出任何缓冲的输出字节

```