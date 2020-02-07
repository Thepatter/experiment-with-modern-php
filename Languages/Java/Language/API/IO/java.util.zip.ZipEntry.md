*java.util.zip.ZipEntry*
```java
ZipEntry(String name);

// 用给定的名字构建一个 Zip 项

// name 		这一项的名字

long getCrc();

// 返回用于这个 `ZipEntry` 的 `CRC32` 校验和的值

String getName();

// 返回这一项的名字

long getSize();

// 返回这一项未压缩的尺寸，或者在未压缩的尺寸不可知的情况下返回 -1

boolean isDirectory();

// 当这一项是目录时返回 true

void setMethod(int method);

// Method 			用于这一项的压缩方法，必须是 `DEFLATED` 或 `STORED`

void setSize(long size);

// 设置这一项的尺寸，只有在压缩方法是 `STORED` 时才是必需的

// size				这一项未压缩的尺寸

void setCrc(long crc);

// 给这一项设置 `CRC32` 校验和，这个校验和是使用 `CRC32` 类计算。只有在压缩方法是 `STORED` 时才是必须的。

// `crc`			这一项的校验和

```