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

* 在 `windows` 中目录的分隔符是反斜杠 （\\) , Java 中应表示为（\\\)
* 构建一个 File 实例并不会在机器上创建一个文件。不管文件是否存在，都可以创建任意文件名的 File 实例，可以滴哦用 File 实例的 `exists()` 方法来判断这个文件是否存在

### 文件输入和输出

__使用 `java.util.Scanner` 类从文件中读取文本数据，使用 `java.io.PrintWriter` 类向文本文件写入数据__

* `printWriter` 写数据 `java.io.printwriter` 可以用来创建一个文件并向文本文件写入数据。

  ```java
  PrintWriter(file: File)		// 为指定的文件对象创建一个 printwriter 对象
  PrintWrinter(filename: String)	// 为指定的文件名字符串创建一个 printWriter 对象
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

