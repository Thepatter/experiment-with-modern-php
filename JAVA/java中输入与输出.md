## java 中输出与正则相关

### 输入与输出流

可以从其中读入一个字节序列的对象称为输入流，而可以向其中写入一个字节序列的对象称为输出流。这些字节序列的来源地和目的地可以是文件、网络连接、内存块。抽象类 `InputStream` 和 `OutputStream` 是输入输出的基类

因为面向字节的流不便于处理以 `Unicode` 形式存储信息（Unicode 中每个字符都使用了多个字节来表示），所以从抽象类 `Reader` 和 `Writer` 中继承出来了一个专门用于处理 `Unicode` 字符的单独的类层次结构。这些类拥有的读入和写出操作都是基于两字节的 `Char` 值（即，`Unicode` 码元），而不是基于 `byte` 值的

#### 读写字节

`InputStream` 类的抽象方法

```java
// 读入一个字节，并返回读入的字节，或者在遇到输入源结尾时返回 -1
abstract int read();
```

在设计具体的输入流时，必须覆盖这个方法以提供适用的功能。`InputStream` 类还有若干非抽象的方法，它们可以读入一个字节数组，或者跳过大量的字节。这些方法都要调用抽象的 `read` 方法，因此各个子类都只需覆盖这一个方法

`OutputStream` 类定义了下面的抽象方法

```java
// 向某个输出位置写一个字节
abstract void write(int b);
```

`read` 和 `write` 方法在执行时都将阻塞，直至字节确实被读入或写出。这就意味着如果不能被立即访问，那么当前的线程将被阻塞。这使得在这两个方法等待指定的流变为可用的这段时间里，其他的线程就有机会去执行有用的工作

`available` 方法可以检查当前可读入的字节数量

```java
// 该代码片段不会被阻塞
int bytesAvailable = in.available();
if (bytesAvailable > 0) {
    byte[] data = new byte[bytesAvailable];
    in.read(data);
}
```

完成输入、输出流的读写时，应该通过调用 `close` 方法来关闭它，这个调用会释放掉占用的操作系统资源。关闭一个输出流的同事还会冲刷用于该输出流的缓冲区；所有被临时置于缓冲区中，以便用更大的包的形式传递的字节在关闭输出流时都将被送出。特别是，如果不关闭文件，那么写出字节的最后一个包可能将永远也得不到传递。可以使用 `flush` 方法来手动冲刷这些输出

#### java 中流家族

*java输入输出流家族*

![](./Images/java输入输出流家族.png)

对 `Unicode` 文本，可以使用抽象类 `Reader` 和 `Writer` 的子类

![](./Images/reader和writer的层次结构.png)

还有 4 个附加的接口：`Closeable`、`Flushable`、`Readable`、`Appendable` 前两个接口非常简单，分别拥有下面的方法

```java
void close() throws IOException
void flush()
```

`InputStream` 、`OutputStream` 、`Reader` 、`Writer` 都实现了 `Closeable` 接口。`java.io.Closeable` 接口扩展了 `java.lang.AutoCloseable` 接口。对任何 `Closeable` 进行操作时，都可以使用 `try-with-resource` 语句。`Closeable` 接口的 `close` 方法只抛出 `IOException`，而 `AutoCloseable.close` 方法可以抛出任何异常

![](./Images/CloseableFlushable接口.png)

而 `OutputStream` 和 `Writer` 还实现了 `Flushable` 接口。

`Readable` 接口只有一个方法

```java
int read(CharBuffer cb)
```

`CharBuffer` 类拥有按顺序和随机地进行读写访问的方法，它表示一个内存中的缓冲区或一个内存映像的文件

`Appendable` 接口有两个用于添加单个字符和字符序列的方法

```java
Appendable append(char c)
Appendable append(CharSequence s)
```

只有 `Writer` 实现了 `Appendable` 接口

#### 组合输入、输出流过滤器

`FileInputStream` 和 `FileOutputStream` 可以提供附着在一个磁盘文件上的输入流和输出流，需要向构造器提供文件名或文件的完整路径名。所有在 `java.io` 中的类都将相对路径名解释为以用户工作目录开始，可以通过调用 `System.getProperty("user.dir")` 来获得这个信息。这些类只支持在字节级别上的读写。即只能读入字节和字节数组

### 文本输入与输出

在保存数据时，可以选择二进制格式或文本格式。在存储文本字符串时，需要考虑字符编码方式。在 Java 内部使用的 `UTF-16` 编码方式。

`OutputStreamWriter` 类将使用选定的字符编码方式，把 `Unicode` 码元的输出流转换为字节流。而 `InputStreamReader` 类将包含字节（用某种字符编码方式表示的字符）的输入流转换为可以产生 `Unicode` 码元的读入器。应该总是在 `InputStreamReader` 的构造器中选择一种具体的编码方式

对于文本输出，可以使用 `PrintWriter` 。这个类拥有以文本格式打印字符串和数字的方法

```java
PrintWriter out = new PrintWriter("employee.txt", "UTF-8");
// 等同于以下代码
PrintWriter out = new PrintWriter(new FileOutputStream("employee.txt"), "UTF-8");
```

如果写出器设置为自动冲刷模式，只要 `println` 被调用，缓冲区中的所有字符都会被发送到它们的目的地（打印写出器总是带缓冲区的）。默认情况下，自动冲刷机制是禁用的。可以通过使用 `PrintWriter(Writer out, Boolean autoFlush)` 来启用或禁用自动冲刷机制。`print` 方法不抛出异常，可以调用 `checkError` 方法来查看输出流是否出现了某些错误

#### 读入文本输入

最简单的处理任意文本的方式就是使用 `Scanner` 类。可以从任何输入流中构建 `Scanner` 对象

```java
// 将短小的文本文件像下面这样读入到一个字符串中
String content = new String(Files.readAllBytes(path), charset);
// 将这个文件一行行的读入
List<String> lines = Files.readAllLines(path, charset);
// 如果文件太大，可以将行惰性处理为一个 Stream<String> 对象
try (Stream<String> lines = Files.lines(path, charset))
{
    
}
```

`BufferedReader` 类有一个 `lines` 方法，可以产生一个 `Stream<String>` 对象，`BufferedReader` 没有用于任何读入数字的方法

#### 字符编码方式

输入和输出流都是用于字节序列的。Java 针对字符使用的是 `Unicode` 标准。每个字符或“编码点”都具有一个 21 位的整数。有多种不同的字符编码方式，即将这些 21 位数字包装成字节的方法有多种，最常见的是 `UTF-8`。它将每个 `Unicode` 编码点编码为 1 到 4 个字节的序列。好处是传统的包含了英语中用到的所有字符的 ASCII 字符集中的每个字符都只会占用一个字节。Java 中使用的是 `UTF-16` ，它会将每个 `Unicode` 编码点编码为 1 个或 2 个 16 位值。有两种形式的 `UTF-16` 高位优先和低位优先。为了表示使用的是哪一种格式，文件可以以"字节顺序标记"开头，这个标记为 16 位数值 `0xFEFF`。读入器可以使用这个值来确定字节顺序。然后丢其它

#### 读写二进制数据

`DataOutput` 接口定义了用于二进制写数组，字符，BOOLEAN 值和字符串的方法。

#### 随机访问文件

`RandomAccessFile` 类可以在文件中的任何位置查找或写入数据。磁盘文件都是随机访问的，但是与网络套接字通信的输入、输出流却不是。打开一个随机访问文件，只用于读入或者同时用于读写，可以通过字符串 `r` 用于读入访问或 `rw` 用于读入、写出访问，作为构造器的第二个参数来指定这个选项

```java
RandomAccessFile in = new RandomAccessFile("employee.dat", "r");
RandomAccessFile inOut = new RandomAccessFile("employee.dat", "rw");
```

将已有文件作为 `RandomAccessFile` 打开时，这个文件并不会被删除

随机访问文件有一个表示下一个将被读入或写出的字节所处位置的文件指针，`seek` 方法可以用来将这个文件指针设置到文件中的任意字节位置，`seek` 的参数是一个 `long` 类型的整数，它的值位于 0 到文件按照字节来度量的长度之间。`getFilePointer` 方法返回文件指针的当前位置

`RandomeAccessFile` 类同时实现了 `DataInput` 和 `DataOutput` 接口。

#### ZIP 文档

ZIP 文档通常以压缩格式存储了一个或多个文件，每个 ZIP 文档都有一个头，包含诸如每个文件名字和所使用的压缩方法等信息。Java 中，可以使用 `ZipInputStream` 来读入 ZIP 文档。`getNextEntry` 方法可以返回一个描述这些项的 `ZipEntry` 类型的对象。向 `ZipInputStream` 的 `getInputStream` 方法传递该项可以获取用于读取该项的输入流。然后调用 `closeEntry` 来读入下一项。

要写出到 ZIP 文件，可以使用 `ZipOutputStream` ，而对于希望放入到 ZIP 文件中的每一项，都应该创建一个 `ZipEntry` 对象，并将文件名传递给 `ZipEntry` 的构造器，它将设置其他诸如文件日期和解压缩方法等参数。使用 `ZipOutputStream` 的 `putNextEntry` 方法来开始写出新文件，并将文件数据发送到 ZIP 输出流中。当完成时，需要调用 `closeEntry`