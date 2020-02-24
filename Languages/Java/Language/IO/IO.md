### I/O

#### I/O 体系

##### java I/O 系统

存在各种 I/O 端和想要与之通信的接收端（文件、控制台、网络等），而且还需要以多种不同的方式与它们进行通信（顺序、随机存取、缓冲、二进制、按字符、按行、按字等）。

java 通过创建大量的类来解决 I/O 问题，自 1.0 开始，在原来面向字节的类中添加了面向字符和基于 Unicode 的类。jdk 1.4 中，添加了 nio 类改进性能及功能。

#### 文件体系

##### *File*

<u>既能代表一个特定文件的名称，又能代表一个目录下的一组文件的名称。</u>如果它指一个文件集，可以对此集合调用 list() 方法。可以用 *File* 对象创建新的目录或整个目录路径。

<u>如果以 *FileOutputStream* 或 *FileWriter* 打开，那么它肯定会被覆盖，应该先使用 *File* 判断</u>

#### 输入输出

java 1.0 类库中的 I/O 类分成输入和输出两部分：

* 通过继承任何自 *InputStream* 派生而来的类都具有 read() 方法，用于读取单个字节或字节数组
* 通过继承任何自 *OutputStream* 派生而来的类都具有 write() 方法，用于写单个字节或字节数组

但通常不会用到这些方法，很少使用单一的类来创建流对象，而是通过叠合多个对象来提供所期望的功能（装饰器）创建单一的结果流，需要创建多个对象。面向字节操作。

java 1.1 类库*Reader* 或 *Writer* 的兼容 Unicode 与面向字符的 I/O 功能。主要为了国际化。面向字符操作

*InputStreamReader* 可以将 *InputStream* 转换成 Reader。*OutputStreamWriter* 可将 *OutputStream* 转换为 *Writer*。几乎所有原始的 I/O 流库都有相应的 *Reader* 和 *Writer* 类来提供天然的 Unicode 操作。

对应类

|           1.0 stream            |               1.1                |
| :-----------------------------: | :------------------------------: |
|           InputStream           | Reader 适配器 InputStreamReader  |
|          OutputStream           | Writer 适配器 OutputStreamWriter |
|         FileInputStream         |            FileReader            |
|        FIleOutputStream         |            FileWriter            |
| StringBufferInputStream(已弃用) |           StringReader           |
|                                 |           StringWriter           |
|      ByteArrayInputStream       |         CharArrayReader          |
|      ByteArrayOutputStream      |         CharArrayWriter          |
|        PipedInputStream         |           PipedReader            |
|        PipedOutputStream        |           PipedWriter            |

##### 格式化输出

System.out.printf 格式化输出类似 c 的 printf 每一个以 % 字符开始的格式说明符都用相应的参数替换。

*用于 printf 的转换符*

|  转换符  |         类型         |           例子            |
| :------: | :------------------: | :-----------------------: |
|    d     |      十进制整数      |            156            |
|    x     |     十六进制整数     |            8f             |
|    o     |      八进制整数      |            237            |
|    f     |      定点浮点数      |           15.9            |
|    e     |      指数浮点数      |         1.59e+01          |
|    g     |      通用浮点数      |            --             |
|    a     |    十六进制浮点数    |        0x1.fccdp3         |
|    s     |        字符串        |           Hello           |
|    c     |         字符         |             H             |
|    b     |         布尔         |           True            |
| Tx 或 tx |       日期时间       | 过时，应改为 java.time 类 |
|    %     |        百分号        |             %             |
|    n     | 与平台有关的行分隔符 |            --             |
|    h     |        散列码        |          42628b2          |

* 常用的格式标识符：%b 布尔值，%c 字符，%d 十进制整数，%f 浮点数，%e 标准科学计数法形式的数，%s 字符串指定宽度和精度
  * %5c 输出字符并在这个字符条目前面加 4 个空格
  * %6b 输出布尔值，在 false 值前加一个空格，在 true 值前加两个空格
  * %5d 输出整数条目，宽度至少为 5，如果该条目的数字位数小于 5，就在数字前面加空格，如果该条目的
  * 数字位数大于5，则自动增加宽度
  * %10.2f 输出的浮点条目宽度至少为 10，包括小数点和小数点后两位数字。
  * %10.2e 输出的浮点条目的宽度至少为 10，包括小数点，小数点后两位数字
  * %12s   输出的字符串宽度至少为 12 个字符。如果该字符串条目小于 12 个字符，就在该字符串前加空格，如果该字符串条目多于 12 个字符，则自动增加宽度

##### *InputStream*

用来表示那些从不同数据源产生输入的类。每一种数据源都有相应的子类。

###### *ByteArrayInputStream*

内存缓冲区

###### *StringBufferInputStream*

字符串

###### *FileInputStream*

文件

###### *PipedInputStream*

管道

###### *SequenceInputStream*

将多个 *InputStream* 对象转换成单一 *InputStream*

###### *FilterInputStream*

抽象类，作为装饰器接口，为其他 *InputStream* 类提供有用功能

##### *OutputStream*

###### *ByteArrayOutputStream*

内存缓冲区

###### *FileOutputStream*

文件

###### *PipedOutputStream*

管道

###### *FilterOutputStream*

抽象类，作为装饰器接口

##### ReaderWriter

##### *RandomAccessFile*

使用顺序流打开的文件为顺序访问文件，内容不能更新，需要修改文件，则需使用 *RandomAccessFile* 打开文件，允许在文件的任意位置进行读写，*RandomAccessFile* 类实现了 *DataInput*  和 *DataOutput* 接口

当创建一个 *RandomAccessFile* 时，可以指定两种模式（"r" 只读，或 "rw" 读写)，实例化时，存在则会按指定模式访问，不存在则会创建文件再以指定模式访问

在 jdk 1.4 开始，大多数功能由 nio 存储映射文件所取代

##### IO 流典型使用

###### 缓冲输入文件

使用缓冲读文件：

1. 使用 *String* 或 *File* 对象作为文件名的 *FileInputReader*
2. 使用 *BufferedReader* 构造器
3. readLine() 返回 null 时，到达文件末尾

###### 格式化内存输入

要读取格式化数据，使用 *DataInputStream* 面向字节的 I/O 类。

###### 基本文件输出

*FileWriter* 对象可以向文件写入数据。首先创建一个与指定文件连接的 *FileWriter*，通常会用 *BufferedWriter* 将其包装起来缓冲输出。

##### 压缩

I/O 类库中的类支持读写压缩格式的数据流，可以用它们对其他的 I/O 类进行封装，以提供压缩功能。这些类不是从 *Reader* 和 *Writer* 类派生而来的，而是属于 *InputStream* 和 *OutputStream* 继承层次结构的一部分。这样做是因为压缩类库是按字节而不是字符方式处理的。

压缩类的使用非常直观，直接将流封装在压缩类中，其他全部操作就是通常的 I/O 读写。

###### *CheckedInputStream*

InputStream 校验相关

###### *CheckedOutputStream*

OutoutStream 校验相关

###### *DeflaterOutputStream*

压缩类的基类

###### *ZipOutputStream*

用于将数据压缩成 Zip 文件格式

对于每一个要加入压缩档案的文件，都必须调用 putNextEntry()，并将其传递给一个 *ZipEntry* 对象（*ZipEntry*）允许获取和设置 Zip 文件内该特定项上所有可利用的数据：名字、压缩和未压缩的文件大小、日期、CRC 校验和、额外字段数据、注释、压缩方法以及它是否是一个目录入口等，只支持 CRC 的接口）

java 的 Zip 类库不支持设置密码。

###### *GZIPOutputStream*

用于将数据压缩成 GZIP 文件格式

GZIP 接口比较简单，如果只想对单个数据流（而不是一系列数据）进行压缩，适合使用该对象

###### *InflaterInputStream*

解压缩

###### *ZipInputStream*

解压缩 Zip 文件

*ZipInputStream* 提供了一个 getNextEntry() 方法返回下一个 *ZipEntry* 或调用 ZipFile.enties() 获取一个枚举

###### *GZIPInputStream*

解压缩 GZIP 文件

#### 标准 IO

类似 unix 标准 IO。按照标准 I/O 模型，java 提供了 System.in、System.out、System.err。System.err 和Sytem.out 已经被包装为 *PrintStream* 对象。System.in 为 *InputStream*

*System* 提供了一些简单的静态方法调用，允许进行对标准流进行重定向。setIn()、setOut()、setErr()。I/O 重定向操作的是字节流

#### NIO

##### nio 机制

jdk 1.4 的 java.nio.* 包中引入了新的 java I/O 类库，其目的在于提高速度。实际上，旧的 I/O 包已经使用 nio 重新实现过，以便充分利用这种速度提高。即使不显式地用 nio 包编写代码，也能从中受益。

速度提高来自于使用结构更接近于操作系统执行 I/O 的方式：通道和缓冲器。唯一直接与通道交互的缓冲器是 *ByteBuffer*，可以存储未加工字节的缓冲器。

###### 通道

旧 I/O 类库中有三个类被修改了：*FileInputStream*、*FileOutputStream*、*RandomAccessFile*，用以产生 *FileChannel*。这些是字节操纵流，与底层的 nio 性质一致。*Reader* 和 *Writer* 这种字符模式不能用于产生通道。*java.nio.channels.Channels* 可以从通道产生 *Reader* 和 *Writer*。对于上面三个流类，getChannel() 将会产生一个 *FileChannel*。通道是相当基础的东西，可以向它传送用于读写的 *ByteBuffer*，并且可以锁定文件的某些区域用于独占式访问

###### 缓冲器

缓冲器是由具有相同类型的数值构成的数组，*Buffer* 类是一个抽象类，它有众多的具体子类，包括 *ByteBuffer*、CharBuffer、DoubleBuffer、IntBuffer、LongBuffer、ShortBuffer

​    *缓冲区的典型结构*

​    ![](/Users/zhangyaowen/notes/Languages/Java/Language/Images/buffer缓冲区结构.png)

* 一个容量，它永远不能改变
* 一个读写位置，下一个值将在此进行读写
* 一个界限，超过它进行读写时没有意义的
* 一个可选的标记，用于重复一个读入或写出操作

这些值满足下面的条件

0 <= 标记 <= 位置 <= 界限 <= 容量

使用缓冲区的主要目的是执行 『写，然后读入』 循环：

1. 假设有一个缓冲区，在一开始，它的位置为 0，界限等于容量。
2. 不断调用 put() 将值添加到这个缓冲区中，当耗尽所有的数据或者写出的数据量达到容量大小时，就该切换到读入操作了。
3. 调用 flip() 方法将界限设置到当前位置，并把位置复位到 0。在 remaining() 方法返回正数时（返回的值为『界限 - 位置』），不断地调用 get() 。在将缓冲区中所有的值都读入之后，调用 clear() 使缓冲区为下一次写循环做好准备。clear() 方法将位置复位到 0，并将界限复位到容量

##### *ByteBuffer*

将字节存放于 *ByteBuffer*：使用 put() 方式填充；或使用静态 warp() 将已存在的字节数组『包装』到 *ByteBuffer*，使用该方式，就不再复制底层的数组，而是把它作为所产生的 *ByteBuffer* 的存储器。对于只读访问必须显式地使用静态 allocate() 方法来分配 *ByteBuffer*。为了达到更高的速度，可以使用 allocateDirect() 以产生一个与操作系统有更高耦合性的『直接』缓冲器。但分配开支会更大。

```java
// 读写 fileChannel
FileChannel in = new FileInputStream(args[0]).getChannel();
FileChannel out = new FileOutputStream(args[1]).getChannel();
ByteBuffer buffer = ByteBuffer.allocateDirect(4096);
while(in.read(buffer) != -1) {
    buffer.flip();
    out.write(buffer);
    buffer.clear();
}
// 通道直接相连
in.transferTo(0, in.size(), out);
```

缓冲器容纳的是普通的字节，为了把它们转换成字符。要么在输入它们的时候对其进行编码，要么将其从缓冲器输出时对其进行解码。

尽管 *ByteBuffer* 只能保存字节类型的数据，但它具有可以从其所容纳的字节中产生出各种不同基本类型值的方法。分配 *ByteBuffer* 后默认将其初始化为 0。向 *ByteBuffer* 插入基本类型数据的最简单的方法是使用 asIntBuffer() 等获取该缓冲器上的基本类型视图，然后使用视图的 put() 方法。基本类型视图支持修改，修改会映射到底层 *ByteBuffer*。*ByteBuffer* 以高位优先的形式存储数据，且可以指定低位优先或高为优先。

##### 内存映射文件

内存映射文件允许创建和修改那些因为太大而不能放入内存的文件。有了内存映射文件，可以假定整个文件都在内存中，当作数组来访问。

*MappedByteBuffer* 继承 *ByteBuffer*。nio 使『映射文件访问』性能显著提升

##### 文件加锁

jdk 1.4 引入文件加锁机制，它允许同步访问某个作为共享资源的文件。不过竞争同一个文件的两个线程可能在不同的 jvm 上。或者一个 java 线程或操作系统其他本地线程。文件锁对其他的操作系统进程可见。java 的文件加锁直接映射到本地操作系统的加锁工具

通过对 *FileChannel* 调用 tryLock() 或 lock()，就可以获得整个文件的 *FileLock*（*SocketChannel*、*DatagramChannel*、*ServerSocketChannel*）不需要加锁，因为它们是从单进程实体继承而来；通常不在两个进程之间共享网络 socket。

tryLock() 是非阻塞式的，它设法获取锁，如果不能获得，直接从方法调用返回。lock() 是阻塞式的，它要阻塞进程直至锁可以获得，或调用 lock() 的线程中断，或调用 lock() 的通道关闭。使用 release() 释放锁。无参数的加锁会随着文件尺寸的变化而变化

支持范围读写锁，具有固定尺寸的锁不随文件尺寸的变化而变化。如果获得某一区域上的锁，当文件增大超过锁定区域时，超出部分不会锁定。对独占锁或共享锁的支持必须由底层的操作系统提供。如果操作系统不支持共享锁并为每一个请求都创建一个锁，那么它就会使用独占锁。

###### 对映射文件的部分加锁

文件映射通常应用于极大的文件，可能需要对这种巨大的文件进行部分加锁，以便其他进程可以修改文件中未被加锁的部分。

#### 对象IO

##### Serializable 与 Externalizable 接口

java 对象序列化将哪些实现了 Serializable 接口（标记接口，不包含任何方法）的对象转换成一个字节序列，并能够在以后将这个字节序列完全恢复为原来的对象。

要序列化一个对象，首先要创建某些 *OutputStream* 对象，然后将其封装在一个 *ObjectOutputStream* 对象内。调用 writeObject() 即可将对象序列化，并将其发送给 *OutputStream* (对象序列化是基于字节的，要使用 *InputStream* 和 *OutputStream* 继承2层次结构)；反序列化一个对象，需要将一个 *InputStream* 封装在 *ObjectInputStream* 内，然后调用 readObject()，除非能验证对象，否则必须保证 jvm 能找到相关的 .class 文件

特殊情况下，可通过实现 Externalizable 接口来代替 Serializable 接口，来对序列化过程进行控制，Externalizable 接口继承 Serializable 接口，同时增加了 writeExternal() 和 readExteernal() 这两个方法会在序列化和反序列化还原的过程中自动调用。

<u>对于恢复 Serializable 对象，对象完全以它存储的二进制位为基础来构造，而不调用构造器，而对于一个 Externalizable 对象，所有普通的默认构造器都会被调用（包括字段定义时的初始化），然后调用 readExternal()</u>

<u>Class 是 Serializable 的，但想序列化 static 值，必须手动实现</u>

##### transitent 关键字

对序列化进行控制时，为防止对象的敏感部分被序列化，可以将类实现为 Externalizable，这样没有任何东西可以自动序列化，并且可以在 writeExternal() 内部只对所需部分进行显式的实例化

<u>如果正在操作的是一个 Serializable 对象，那么所有序列化操作都会自动进行，可以用 transient 关键字逐个字段地关闭序列化</u>

