*java.io.InputStream*
```java
// * 抽象类 `InputStream` 是读取二进制数据的根类。几乎所有的 I/O 类中的方法都会抛出异常 `java.io.IOException`,因此须在方法中声明抛出

abstract int read();

// 从输入流中读取下一个字节数据。字节值以 0 到 255 取值范围的 `int` 值返回。如果因为已经达到流的最后而没有可读的字节，则返回值 -1

int read(byte[] b);

// 读入一个字节数组、并返回实际读入的字节数，或者在碰到输入流的结尾时返回 -1，最多读入 `b.length` 个字节

int read(byte[] b, int off, int, len);

// 读入一个字节数组。这个 `read` 方法返回实际读入的字节数，或者在碰到输入流的结尾时返回 -1

// b			数据读入的数组

// ​	off		       第一个读入字节应该被放置的位置在 b 中的偏移量

// ​	len			读入字节的最大数量

int available();

// 返回可以从输入流中读取的字节数的估计值

void close();

// 关闭输入流，释放其占用的任何系统资源

long skip(long n);

// 从输入流中跳过并且丢弃 n 字节的数据，返回实际跳过的字节数（如果碰到输入流的结尾，则可能小于 n）

boolean markSupported();

// 测试该输入流是否支持 `mark` 和 `reset` 方法

void mark(int readLimit);

// 在输入流的当前位置打开一个标记（并非所有流都支持这个特性）。如果从输入流中已经读入的字节多于 `readlimit` 个，则这个流允许忽略这个标记

void reset();

// 返回到最后一个标记，随后对 `read` 的调用将重新读入这些字节。如果当前没有任何标记，则这个流不被重置

```