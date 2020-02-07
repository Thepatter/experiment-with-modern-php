*java.io.PushbackInputStream*
```java
PushbackInputStream(InputStream in);

PushbackInputStream(InputStream in, int size);

// 构建一个可以预览一个字节或者具有指定尺寸的回推缓冲区的输入流

void unread(int b);

// 回推一个字节，它可以在下次调用 read 时被再次获取

// b	要再次读入的字节

```