*java.io.RandomAccessFile*
```java
// * __使用顺序流打开的文件为顺序访问文件，内容不能更新，需要修改文件，则需使用 `RandomAccessFile` 类打开文件，允许再文件的任意位置进行读写，`RandomAccessFile` 类实现了 `DataInput` 和 `DataOutput` 接口__

// * 当创建一个 `RandomAccessFile` 时，可以指定两种模式（"r" 只读，或 "rw" 读写)，当实例化 `RandomAccessFile` 时，存在则会按指定模式访问，不存在则会创建文件再以指定模式访问

RandomAccessFile(String file, String mode);

RandomAccessFile(File file, String mode);

// file			要打开的文件

// mode		`r` 只读，`rw` 读写，`rws` 每次更新时，都对数据和元数据的写磁盘操作进行同步的读、写模式，`rwd` 每次更新时，只对数据的写磁盘操作进行同步的读、写模式

void close();

// 关闭且释放

long getFilePointer();

// 返回文件指针的当前位置

void seek(long pos);

// 将文件指针设置到距文件开头 `pos` 个字节处

void length();

// 返回文件按照字节来度量的长度

long length();

// 返回该文件中的字节数

int read();

// 从该文件中读取一个字节数据，再流的末尾返回 -1

int read(b: byte[]);

// 从该文件中读取 b.length 个字节数据到一个字节数组中

int read(B: byte[], off: int, len: int);

// 从该文件中读取 len 个字节数据到一个字节数组中

void seek(pos: long);

// 设置从流开始位置计算的偏移量（在 pos 值中设置的以字节为单位的，下一个 read 或者 write 将从该位置进行

void setLength(newLength: long);

// 为该文件设置一个新的长度

int skipBytes(int n);

// 跳过 n 个字节的输入

void write(b: byte[]);

// 从指定的字节数组中写 b.length 个字节到该文件中，从当前的文件指针开始写入

void write(b: byte[], off: int, len: int);

// 从偏移量 off 开始，从指定的字节数组中写 len 个字节到该文件中

FileChannel getChannel();

// 返回用于访问这个文件的通道

```