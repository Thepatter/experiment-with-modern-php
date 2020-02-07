*java.io.DataInput*
```java
boolean readBoolean();

byte readByte();

char readChar();

double readDouble();

float readFloat();

int readInt();

long readlong();

short readShort();

// 读入一个给定类型的值

void readFully(byte[] b);

// 将字节读入到数组 b 中，其间阻塞直至所有字节都读入

void readFully(byte[] b, int off, int len);

// 将字节读入到数组 b 中，其间阻塞直至所有字节都读入

// b 		数据读入的缓冲区

// off		数据起始位置的偏移量

// len		读入字节的最大数量

String readUTF();

// 读入由"修订过的 `UTF-8`" 格式的字符构成的字符串

int skipBytes(int n);

// 跳过 n 个字节，其间阻塞直至所有字节都被跳过

// n		被跳过的字节数

```