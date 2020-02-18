## IO 操作

### File 类 `java.io.File`

__File 类包含了一个文件/目录的属性，以及对文件/目录进行改名和删除的方法__

* 在 `windows` 中目录的分隔符是反斜杠 （\\) , Java 中应表示为（\\\\)
* 构建一个 File 实例并不会在机器上创建一个文件。不管文件是否存在，都可以创建任意文件名的 File 实例，可以用 File 实例的 `exists()` 方法来判断这个文件是否存在

### 文件输入和输出

__使用 `java.util.Scanner` 类从文件中读取文本数据，使用 `java.io.PrintWriter` 类向文本文件写入数据__

* `printWriter` 写数据 `java.io.printwriter` 可以用来创建一个文件并向文本文件写入数据。

* 使用 `try（声明和创建资源）{ 使用资源处理文件 }` 来处理文件，会自动调用 close 关闭资源


#### 从 web 上读取数据

为了读取一个文件，首先要使用 `java.net.URL` 类的这个构造方法，为该文件创建一个 URL 对象，出错抛出 `MalformedURLException` 可以使用 URL 类中定义的 `openStream()` 方法来打开输入流和用输入流创建 `Scanner` 对象

#### 从控制台上读取数据 `Scanner(System.in)`


* `FilterInputStream` 过滤器数据流是为某种目的的过来字节的数据流。基本字节输入流提供的读取方法 read 只能用来读取字节，如果要读取整数值，双精度值或字符串，那就需要一个过滤器类来包装字节输入流。使用过滤器类就可以读取整数值，双精度值和字符串，而不是字节或字符。`FilterInputStream` 类和 `FilterOutputStream` 类是过滤数据的集类。需要处理基本数值类型时，就使用 `DataInputStream` 类和 `DataOutputStream` 类来过滤字节

* `DataOutputStream` 将基本类型的值或字符串转换为字节，并且将字节输出到数据流。扩展 `FilterOutputStream` 类，并实现 `DataOutput` 接口


* `BufferedInputStream` 类和 `BufferedOutputStream` 类可以通过减少磁盘读写次数来提供输入和输出速度。使用 `BufferedInputStream` 时，磁盘上的整块数据一次性地读入到内存中的缓冲区中，然后从缓冲区中将个别的数据传递到程序中。使用 `BufferedOutputStream` ，个别的数据首先写入到内存中的缓冲区中，当缓冲区已满时，缓冲区中所有的数据一次性写入到磁盘中，如果没有指定缓冲区的大小，默认的大小是 512 字节，对于大文件应该使用缓冲区来加速。

  ```java
  BufferedInputStream(in: InputStream) 	// 从一个 InputStream 对象创建一个 BufferedInputStream
  BufferedInputStream(in: InputStream, bufferSize: int)	// 从一个 InputStream 对象创建一个 BufferInputStream，并指定缓冲区大小
  ```

#### 二进制 I/O 中的字符与字符串

* [Unicode](https://baike.baidu.com/item/Unicode/750500)（统一码、万国码、单一码）是一种在计算机上使用的[字符编码](https://baike.baidu.com/item/%E5%AD%97%E7%AC%A6%E7%BC%96%E7%A0%81/8446880)。它为每种语言中的每个字符设定了统一并且唯一的[二进制编码](https://baike.baidu.com/item/%E4%BA%8C%E8%BF%9B%E5%88%B6%E7%BC%96%E7%A0%81/1758517)，以满足跨语言、跨平台进行文本转换、处理的要求。1990年开始研发，1994年正式公布。随着计算机工作能力的增强，Unicode也在面世以来的十多年里得到普及

* 一个统一码由两个字节构成。`writeChar(char c)` 方法将字符 c 的统一码写入输出流。`writeChars(String s)` 方法将字符串 s 中的所有字符的统一码写道输出流中。`writeBytes(String s)` 方法将字符串 `s` 中每个字符统一码的低字节写到输出流。统一码的高字节被丢弃。`writeBytes` 方法适用于由 ASCII　码字符构成的字符串，因为　ASCII　码仅存储统一码的低字节。如果一个字符串包含非　ASCII　码的字符，必须使用　`writeChars` 方法实现写入这个字符串

* `writeUTF(String s)` 方法将两个字节的长度信息写入输出流，后面紧跟的是字符串 s 中每个字符的改进版 UTF-8 的形式。将字符串转化成 UTF-8 格式的一串字节，然后将它们写入一个输出流。`readUTF` 方法读取一个使用 `writeUTF` 方法写入的字符串

#### 检测文件的末尾

__如果到达 `InputStream` 的末尾之后还继续从中读取数据，就会发生 `EOFException` 异常，这个异常可以用来检测是否已经到达文件末尾__

### 对象 IO 及序列化

* `ObjectInputStream`，`ObjectOutputStream` 可以实现基本数据类型和字符串的及对象的输入输出。`ObjectInputStream` 扩展`InputStream`,`OutputStream` 并实现 `ObjectInput` `ObjectStreamConstants`。`ObjectOutputStream` 扩展 `OutputStream` 类，并实现 `ObjectOutput` 与 `ObjectStreamConstants`

  ```java
  public ObjectInputStream(InputStream in)
  public ObjectOutputStream(OutputStream out)
  ```

* `readObject` 方法可能会抛出异常 `java.lang.ClassNotFoundException` 这是因为 `java`  虚拟机恢复一个对象时，如果没有加载该对象所在的类，就应该先加载这个类。因为 `ClassNotFoundException` 异常是一个必检异常，所以要在 `main` 方法中抛出它

* 并不是每一个对象都可以写入到输出流，可以写入输出流中的对象称为可序列化的，可序列化的对象的类必须实现 `java.io.Serializable` 接口。`Serializable` 接口是一种标记接口。因为它没有方法，不需要在类中为实现 `Serializable` 接口增加额外的代码。实现这个接口可以启动 Java 的序列化机制自动完成存储对象和数组的过程
* 当存储一个可序列化对象时，会对该对象的类进行编码。编码包括类名，类的签名，对象实例变量的值以及该对象引用的任何其他对象的闭包，但是不存储对象静态变量的值
* 如果一个对象是 `Serializable` 的实例，但它包含了非序列化的实例数据域，那么不能序列化该对象，必须给非序列化的实例数据域加上关键字 `transient` 来让 Java 虚拟机忽略这些数据域
* 如果一个对象不止一次写入对象流，不会存储多个副本，第一次写入一个对象时，就会为它创建一个序列号。Java 虚拟机将对象的所有内容和序列号一起写入对象流，以后每次存储时，如果再写入相同的对象，就只存储序列号。读这些对象时候，它们的引用相同
* 如果数组中的所有元素都是可序列化的，这个数组就是可序列化的。一个完整的数组可以用 `writeObject` 方法存入文件，随后用 `readObject` 方法恢复

### 随机访问文件

