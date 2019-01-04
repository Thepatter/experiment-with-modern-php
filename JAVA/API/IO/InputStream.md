## java.io.InputStream

* 抽象类 `InputStream` 是读取二进制数据的根类。几乎所有的 I/O 类中的方法都会抛出异常 `java.io.IOException`,因此须在方法中声明抛出

* `int read()`

    从输入流中读取下一个字节数据。字节值以 0 到 255 取值范围的 `int` 值返回。如果因为已经达到流的最后而没有可读的字节，则返回值 -1
    
* `int read(byte[] b)`

    从输入流中读取 `b.length` 个字节到数组 b 中，并且返回实际读取的字节数
    
* `int read(byte[] b, int off, int, len)`

    从输入流中读取字节并且将它们保存在 b[off], b[off +1]...b[0ff + len -1] 中。返回实际读取的字节数。到流的最后时返回 -1
    
* `int available()`

    返回可以从输入流中读取的字节数的估计值
    
* `void close()`

    关闭输入流，释放其占用的任何系统资源
    
* `long skip(long n)`

    从输入流中跳过并且丢弃 n 字节的数据，返回实际跳过的字节数
    
* `boolean markSupported()`

    测试该输入流是否支持 `mark` 和 `reset` 方法
    
* `void mark(int readLimit)`

    在该输入流中标记当前位置
    
* `void reset()`

    将该流重新定位到最后一次调用 `mark` 方法时的位置
    