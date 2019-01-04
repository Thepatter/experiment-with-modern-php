## java.io.RandomAccessFile

* __使用顺序流打开的文件为顺序访问文件，内容不能更新，需要修改文件，则需使用 `RandomAccessFile` 类打开文件，允许再文件的任意位置进行读写，`RandomAccessFile` 类实现了 `DataInput` 和 `DataOutput` 接口__

* 当创建一个 `RandomAccessFile` 时，可以指定两种模式（"r" 只读，或 "rw" 读写)，当实例化 `RandomAccessFile` 时，存在则会按指定模式访问，不存在则会创建文件再以指定模式访问

* `RandomAccessFile(file: File, mode: String)`
 
  使用指定的 File 对象和模式创建 RandomAccess File 流

* `RandomAccessFile(name: String, mode: String)`
  
  使用指定的文件名字符串和模式创建 RandomAccessFile 流

* `void close()`
  
  关闭且释放

* `long getFilePointer()`	

  返回以字节计算的从文件开始的偏移量，下一个 read 或者 write 将从该位置进行

* `long length()`
    
  返回该文件中的字节数

* `int read()`
			
  从该文件中读取一个字节数据，再流的末尾返回 -1

* `int read(b: byte[])`
  
  从该文件中读取 b.length 个字节数据到一个字节数组中

* `int read(B: byte[], off: int, len: int)`
  
  从该文件中读取 len 个字节数据到一个字节数组中

* `void seek(pos: long)`

  设置从流开始位置计算的偏移量（在 pos 值中设置的以字节为单位的，下一个 read 或者 write 将从该位置进行

* `void setLength(newLength: long)`
  
  为该文件设置一个新的长度

* `int skipBytes(int n)`
  
  跳过 n 个字节的输入

* `void write(b: byte[])`
 
  从指定的字节数组中写 b.length 个字节到该文件中，从当前的文件指针开始写入

* `void write(b: byte[], off: int, len: int)`
 
  从偏移量 off 开始，从指定的字节数组中写 len 个字节到该文件中