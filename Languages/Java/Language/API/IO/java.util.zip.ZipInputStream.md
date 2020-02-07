*java.util.zip.ZipInputStream*
```java
ZipInputStream(InputStream in);

// 创建一个 `ZipInputStream` ，使用可以从给定的 `InputStream` 向其中填充数据

ZipEntry getNextEntry();

// 为下一项返回 `ZipEntry` 对象，或者在没有更多的项时返回 null

void closeEntry();

// 关闭这个 ZIP 文件中当前打开的项。之后可以通过使用 `getNextEntry()` 读入下一项

```