*java.util.zip.ZipOutputStream*
```java
ZipOutputStream(OutputStream out);

// 创建一个将压缩数据写出到指定的 `OutputStream` 的 `ZipOutputStream`

void putNextEntry(ZipEntry ze);

// 将给定的 `ZipEntry` 中的信息写出到输出流中，并定位用于写出数据的流，然后这些数据可以通过 `write()` 写出到这个输出流中

void closeEntry();

// 关闭这个 ZIP 文件中当前打开的项。使用 `putNextEntry` 方法可以开始下一项

void setLevel(int level);

// 设置后续的各个 `DEFLATED` 项的默认压缩级别。默认值是 `Deflater.DEFAULT_COMPRESSION`。如果基本无效，则抛出 `IllegalArgumentException`

// level 	压缩级别（NO_COMPRESSION） 到 9 (BEST_COMPRESSION)

void setMethod(int method);

// 设置用于这个 `ZipOutputStream` 的默认压缩方法，这个压缩方法会作用所有没有指定压缩方法的项上

// Method			压缩方法，DEFLATED 或 STORED

```