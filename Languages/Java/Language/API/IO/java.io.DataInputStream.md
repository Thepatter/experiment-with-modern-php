*java.io.DataInputStream*
```java
// * `DataInputStream` 从数据流读取字节，并且将它们转换为合适的基本类型值或字符串,`DataInputStream` 类扩展 `FilterInputStream` 类，并实现 `DataInput` 接口

boolean readBoolean();

// 从输入流中读取一个 Boolean 值

byte readByte();

// 从输入流中读取一个 byte 值

char readChar();

// 从输入流中读取一个字符

float readFloat();

// 从输入流中读取一个 float 值

double readDouble();

// 从输入流中读取一个 double 值

int readInt();

// 从输入流中读取一个 int 值

long readLong();

// 从输入流中读取一个 long 值

short readShort();

// 从输入流中读取一个 short 值

String readLine();

// 从输入流中读取一行字符

String readUTF();

// 以 UTF 格式读取一个字符串

```