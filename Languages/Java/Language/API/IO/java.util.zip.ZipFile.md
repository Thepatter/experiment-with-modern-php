*java.util.zip.ZipFile*
```java
ZipFile(String name);

ZipFile(File file);

// 创建一个 `ZipFile` ，用于从给定的字符串或 `File` 对象中读入数据

Enumeration entries();

// 返回一个 `Enumeration` 对象，它枚举了描述这个 `ZipFile` 中各个项的 `ZipEntry` 对象

ZipEntry getEntry(String name);

// 返回给定名字所对应的项，或者在没有对应项的时候返回 null

// name			项目名

InputStream getInputStream(ZipEntry ze);

// 返回用于给定项的 `InputStream`

// ze		这个 ZIP 文件中的一个 `ZipEntry`

String getName();

// 返回这个 ZIP 文件的路径

```