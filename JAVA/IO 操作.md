## IO 操作

### File 类 `java.io.File`

__File 类包含了一个文件/目录的属性，以及对文件/目录进行改名和删除的方法__

```Java
File(pathname: String)			// 为一个指定的路径名创建一个 File 对象，路径名可能是一个目录或者一个文件
File(parent: String, child: String)	// 在目录 parent 下创建一个子路径的 File 对象，子路径可能是一个目录或者一个文件
File(parent: File, child: String)	// 在目录 parent 下创建一个子路径的 File 对象，parent 是一个 File 对象。之前的构造方法中，parent 是一个字符串
exists(): boolean		// File 对象代表的文件和目录存在，返回 true
canRead(): boolean		// File 对象代表的文件存在且可读，返回 true
canWrite():  boolean		// File 对象代表的文件存在且可写，返回 true
isDirectory(): boolean		// File 对象代表的是一个目录，返回 true
isFile(): boolean		// File 对象代表的是一个文件，返回 true
isAbsolute(): boolean	// File 对象采用绝对路径名创建，返回 true
isHidden():	boolean		// 如果 File 对象代表的文件是隐藏的，返回 true。隐藏的确切定义是系统相关的。Windows 系统中，可以在文件属性对话框中标记一个文件隐藏。Unix 系统中，如果文件名以点（.) 开始，则文件时隐藏
getAbsolutePath(): String	// 返回 File 对象代表的文件和目录的完整绝对路径名
getCanonicalPath(): String		// 和 getAbsolutePath() 返回相同，除了从路径名中去掉了冗余的名字，（.和..) 以及符号链接（unix),以及将盘符转换为标准的大写形式
getName(): String		// 返回 File 对象的文件名
getPath(): String		// 返回完整路径和文件名
getParent(): String		// 返回 File 对象代表的当前目录和文件的完整父目录
lastModified(): long		// 返回文件最后修改时间
length(): long		// 返回文件的大小，如果不存在或者是一个目录的话，返回 0
listFile(): File[]		// 返回一个目录 File 对象下面的文件
delete(): boolean		// 删除 File 对象代表的文件或目录，如果删除成功，返回 true
renameTo(dest: File): Boolean	// 将 File 对象代表的文件或者目录改名为 dest 中指定的名字，如果操作成功，返回 true
mkdir(): boolean		// 创建该 File 对象代表的目录，如果目录成功创建，则返回 true
mkdirs(): boolean		// 和 mkdir() 相同，除开在父目录不存在的情况下，将和父目录一起创建
```

* 在 `windows` 中目录的分隔符是反斜杠 （\\) , Java 中应表示为（\\\\)
* 构建一个 File 实例并不会在机器上创建一个文件。不管文件是否存在，都可以创建任意文件名的 File 实例，可以用 File 实例的 `exists()` 方法来判断这个文件是否存在

### 文件输入和输出

__使用 `java.util.Scanner` 类从文件中读取文本数据，使用 `java.io.PrintWriter` 类向文本文件写入数据__

* `printWriter` 写数据 `java.io.printwriter` 可以用来创建一个文件并向文本文件写入数据。

  ```java
  PrintWriter(file: File)		// 为指定的文件对象创建一个 printwriter 对象
  PrintWriter(filename: String)	// 为指定的文件名字符串创建一个 printWriter 对象
  print(s: String): void		// 将字符串写入文件中
  print(c: char): void		// 将字符写入文件
  print(cArray: char[]): void		// 将字符数组写入文件中
  print(i:int): void		// 将 int 值写入文件中
  print(l: long): long	// 将 long 值写入文件中
  print(f: float): float		// 将一个 float 值写入文件中
  print(d: double): void		// 将一个 double  值写入文件中
  print(b: boolean): void		// 将一个 Boolean 值写入文件中
  println()	// 重载的 println() 方法与 print 类似，打印一个换行
  printf()		// 格式化
  close() 	// 关闭文件
  ```

* 使用 `try（声明和创建资源）{ 使用资源处理文件 }` 来处理文件，会自动调用 close 方法

```Java
Scanner(source: File)	// 创建一个 Scanner,从指定的文件中扫描标记
Scanner(source: String)		// 创建一个 Scanner，从指定的字符串中扫描标记
close()			// 关闭该 Scanner
hasNext(): boolean		// 如果该 Scanner 还有更多数据，则返回 true
next(): String		// 从该 Scanner 中读取下一个标记作为字符串返回
nextLine(): String		// 从该 Scanner 中读取一行，以换行结束
nextByte():	Byte		// 从该 Scanner 中读取下一个标记作为 byte 值返回
nextShort(): short		// 
nextInt(): 	int
nextLong():	long
nextFloat():  float
nextDouble(): double
useDelimiter(pattern: String): Scanner	// 设置 Scanner 的分隔符，并且返回该 scanner
```

#### 从 web 上读取数据

__为了读取一个文件，首先要使用 `java.net.URL` 类的这个构造方法，为该文件创建一个 URL 对象，出错抛出 `MalformedURLException` 可以使用 URL 类中定义的 `openStream()` 方法来打开输入流和用输入流创建 `Scanner` 对象 __

#### 从控制台上读取数据 `Scanner("system.in")`

### 二进制 IO

* 抽象类 `InputStream` 是读取二进制数据的根类。几乎所有的 I/O 类中的方法都会抛出异常 `java.io.IOException`,因此须在方法中声明抛出

  ```java
  read(): int // 从输入流中读取下一个字节数据。字节值以 0 到 255 取值范围的 int 值返回。如果因为已经达到流的最后而没有可读的字节，则返回值 -1
  read(b: byte[]): int 	// 从输入流中读取 b.length 个字节到数组 b 中，并且返回实际读取的字节数，到流的最后时返回 -1
  read(b: byte[], off: int, len: int)	int // 从输入流中读取字节并且将它们保存在 b[off], b[off +1]...b[0ff + len -1] 中。返回实际读取的字节数。到流的最后时返回 -1
  available(): int	// 返回可以从输入流中读取的字节数的估计值
  close(): void	// 关闭输入流，释放其占用的任何系统资源
  skip(n: long): long	// 从输入流中跳过并且丢弃 n 字节的数据，返回实际跳过的字节数
  markSupported(): boolean	// 测试该输入流是否支持 mark 和 reset 方法
  mark(readlimit: int): void	// 在该输入流中标记当前位置
  reset(): void		// 将该流重新定位到最后一次调用 mark 方法时的位置
  ```

* 抽象类 `OutputStream` 是写入二进制数据的根类

  ```java
  write(int b): void	// 将指定的字节写入到该输出流中。参数 b 是一个 int 值（byte) b 写入到输出流中
  write(b: byte[]): void	// 将数组 b 中的所有字节写出到输入流中
  write(b: byte[], off: int, len: int): void	// 将 b[off], b[off+1], b[off+len-1] 写入到输出流中
  close(): void	// 关闭该输出流，并且释放其占用的任何系统资源
  flush(): void	// 清掉输出流，强制写出任何缓冲的输出字节
  ```

* `FileInputStream` 类继承自 `InputStream` 类，用于从文件读取字节

  ```java
  FileInputStream(file: File)		// 从一个 File 对象创建一个 FileInputStream
  FileInputStream(filename: String)	// 从一个文件名创建一个 FileInputStream，如果试图为一个不存在的文件创建 FileInputStream 对象，将会发生 java.io.FileNotFoundException 异常
  ```

* `FileOutputStream` 类继承自 `OutputSteam` 类，用于向文件写入字节

  ```java
  FileOutputStream(file: File)	// 从一个 File 对象构建一个 FileOutputStream
  FileOutputStream(filename: String)	// 从一个文件名创建一个 FileOutputStream
  FileOutputStream(file: File, append: boolean)	// 如果 append 为 true，数据将追加到已经存在的文件中
  FileOutputStream(filename: String, append: boolean)		// 如果 append 为 true,数据将追加到已存在的文件中
  ```

* `FilterInputStream` 过滤器数据流是为某种目的的过来字节的数据流。基本字节输入流提供的读取方法 read 只能用来读取字节，如果要读取整数值，双精度值或字符串，那就需要一个过滤器类来包装字节输入流。使用过滤器类就可以读取整数值，双精度值和字符串，而不是字节或字符。`FilterInputStream` 类和 `FilterOutputStream` 类是过滤数据的集类。需要处理基本数值类型时，就使用 `DataInputStream` 类和 `DataOutputStream` 类来过滤字节

* `DataInputStream` 从数据流读取字节，并且将它们转换为合适的基本类型值或字符串,`DataInputStream` 类扩展 `FilterInputStream` 类，并实现 `DataInput` 接口

  `public DataInputStream(InputSteam instream)`

  ```java
  readBoolean(): boolean		// 从输入流中读取一个 Boolean 值
  readByte(): byte		// 从输入流中读取一个 byte 值
  readChar(): char		// 从输入流中读取一个字符
  readFloat(): float		// 从输入流中读取一个 float 值
  readDouble(): double		// 从输入流中读取一个 double 值
  readInt(): int			// 从输入流中读取一个 int 值
  readLong(): long		// 从输入流中读取一个 long 值
  readShort(): short		// 从输入流中读取一个 short 值
  readLine(): String		// 从输入流中读取一行字符
  readUTF():	String		// 以 UTF 格式读取一个字符串
  ```

* `DataOutputStream` 将基本类型的值或字符串转换为字节，并且将字节输出到数据流。扩展 `FilterOutputStream` 类，并实现 `DataOutput` 接口

  `public DataOutputStream(OutputStream outStream)`

  ```java
  writeBoolean(b: boolean): void		// 向输出流中写一个 Boolean 值
  writeByte(v: int): void			// 向输出流中写参数 v 的 8 位低位比特
  writeBytes(s: String): void		// 向输出流中写一个字符串中字符的低位字节
  writeChar(c: char): void		// 向输出流中写一个字符（由两个字节组成）
  writeChars(s: String): void		// 向输出流中依次写一个字符串 s 中的每个字符，每个字符 2 个字节
  writeFloat(v: float): void		// 向输出流中写一个 float 值
  writeDouble(v: double): void		// 向输出流中写一个 double 值
  writeInt(v: int): void		// 向输出流中写一个 int 值
  writeLong(v: long): void		// 向输出流中写一个 long 值
  writeShort(v: short): void		// 向输出流中写一个 short 值
  writeUFT(s: String): void		// 以 UTF 格式写一个字符串 s
  ```

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

__使用顺序流打开的文件为顺序访问文件，内容不能更新，需要修改文件，则需使用 `RandomAccessFile` 类打开文件，允许再文件的任意位置进行读写，`RandomAccessFile` 类实现了 `DataInput` 和 `DataOutput` 接口__

```java
RandomAccessFile(file: File, mode: String)	// 使用指定的 File 对象和模式创建 RandomAccess File 流
RandomAccessFile(name: String, mode: String)	// 使用指定的文件名字符串和模式创建 RandomAccessFile 流
close(): void			// 关闭且释放
getFilePointer(): long		// 返回以字节计算的从文件开始的偏移量，下一个 read 或者 write 将从该位置进行
length(): long		// 返回该文件中的字节数
read(): int			// 从该文件中读取一个字节数据，再流的末尾返回 -1
read(b: byte[]): int	// 从该文件中读取 b.length 个字节数据到一个字节数组中
read(B: byte[], off: int, len: int): int // 从该文件中读取 len 个字节数据到一个字节数组中
seek(pos: long): void		// 设置从流开始位置计算的偏移量（在 pos 值中设置的以字节为单位的，下一个 read 或者 write 将从该位置进行
setLength(newLength: long): void	// 为该文件设置一个新的长度
skipBytes(int n): int		// 跳过 n 个字节的输入
write(b: byte[]): void			// 从指定的字节数组中写 b.length 个字节到该文件中，从当前的文件指针开始写入
write(b: byte[], off: int, len: int) void	// 从偏移量 off 开始，从指定的字节数组中写 len 个字节到该文件中
```

* 当创建一个 `RandomAccessFile` 时，可以指定两种模式（"r" 只读，或 "rw" 读写)，当实例化 `RandomAccessFile` 时，存在则会按指定模式访问，不存在则会创建文件再以指定模式访问

