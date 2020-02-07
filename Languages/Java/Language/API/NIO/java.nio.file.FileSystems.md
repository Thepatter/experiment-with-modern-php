*java.nio.file.FileSystems*
```java
static FileSystem newFileSystem(Path path, ClassLoader loader);

// 对所安装的文件系统提供者进行迭代，并且如果 `loader` 不为 `null`，那么就还迭代给定的类加载器能够加载的文件系统，返回由第一个可以接受给定路径的文件系统提供者创建的文件系统。默认情况下，对于 ZIP 文件系统是有一个提供者的，它接受名字以 `.zip` 或 `.jar` 结尾的文件

static Path getPath(String first, String... more);

// 将给定的字符串连接起来创建一个路径

```