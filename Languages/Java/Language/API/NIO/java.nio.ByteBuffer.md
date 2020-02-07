*java.nio.ByteBuffer*
```java
byte get();

// 从当前位置获得一个字节，并将当前位置移动到下一个字节

byte get(int index);

// 从指定索引处获得一个字节

ByteBuffer put(byte b);

// 向当前位置推入一个字节，并将当前位置移动到下一个字节。返回对这个缓冲区的引用

ByteBuffer put(int index, byte b);

// 向指定索引处推入一个字节。返回对这个缓冲区的引用

ByteBuffer get(byte[] destination);

ByteBuffer get(byte[] destination, int offset, int length);

// 用缓冲区中的字节来填充字节数组，或者字节数组的某个区域，并将当前位置向前移动如如的字节数个位置。如果缓冲区不够大，那么就不会读入任何字节，并抛出 `BufferUnderflowException` 。返回对这个缓冲区的引用

// `destination`	要填充的字节数组

// `offset` 			要填充区域的偏移量

// `length`			要填充区域的长度

ByteBuffer put(byte[] source);

ByteBuffer put(byte[] source, int offset, int length);

// 将字节数组中的所有字节或者给定区域的字节都推入缓冲区中，并将当前位置向前移动写出的字节数个位置。如果缓冲区不够大，那么就不会读入任何字节，并抛出 `BufferUnderflowException` 。返回对这个缓冲区的引用

// `source`     要写出的数组

// `offset`   要写出区域的偏移量

// `length`    要写出区域的长度

Xxx getXxx();

Xxx getXxx(int index);

ByteBuffer putXxx(Xxx value);

ByteBuffer putXxx(int index, Xxx value);

// 获得或放置一个二进制数。`Xxx` 是  `Int`，`Long`，`Short`，`Char`，`Float` 或 `Double` 中的一个

ByteBuffer order(ByteOrder order);

ByteOrder order();

// 设置或获得字节顺序，`order` 的值是 `ByteOrder` 类的常量 `BIG_ENDIAN` 或 `LITTLE_ENDIAN` 中的一个

static ByteBuffer allocate(int capacity);

// 构建具有给定容量的缓冲区

static ByteBuffer wrap(byte[] values);

// 构建具有指定容量的缓冲区，该缓冲区是对给定数组的包装

CharBuffer asCharBuffer();

// 构建字符缓冲区，它是对这个缓冲区的包装。对该字符缓冲区的变更将在这个缓冲区中反映出来，但是该字符缓冲区有自己的位置、界限和标记

```