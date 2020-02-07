*java.nio.file.Files*
```java
static Path createFile(Path path, FileAttribute<?>... attrs);

static Path createDirectory(Path path, FileAttribute<?>... attrs);

static Path createDirectories(Path path, FileAttribute<?>... attrs);

// 创建一个文件或目录，`createDirectories` 方法还会创建路径中所有的中间目录。

static Path createTempFile(String prefix, String suffix, FileAttribute<?> ... attrs);

static Path createTempFile(Path parentDir, String prefix, STring suffix, FileAttribute<?>... attrs);

static Path createTempDirectory(String prefix, FileAttribute<?>... attrs);

static Path createTempDirectory(Path parentDir, String prefix, FileAttribute<?> .. attrs);

// 在适合临时文件的位置，或者在给定的父目录中，创建一个临时文件或目录，返回锁创建的文件或目录的路径

static Path copy(Path from, Path to, CopyOption...options);

static Path move(Path from, Path to, CoptyOption... options);

// 将 `from` 复制或移动到给定位置，并返回 to

static long copy(InputStream from, Path to, CopyOption... options);

static long copy(Path from, OutputStream to, CoptyOption... options);

// 从输入流复制到文件中，或者从文件复制到输入流中，返回复制的字节数

static void delete(Path path);

static boolean deleteIfExists(Path path);

// 删除给定文件或空目录。第一个方法在文件或目录不存在情况下抛出异常，而第二个方法在这种情况下会返回 false

static boolean exists(Path path);

static boolean isHidden(Path path);

static boolean isReadable(Path path);

static boolean isWritable(Path path);

static boolean isExecutable(Path path);

static boolean isRegularFile(Path path);

static boolean isDirectory(Path path);

static boolean isSymbolicLink(Path path);

// 检查由路径指定的文件的给定属性

static long size(Path path);

// 获取文件按字节数度量的尺寸

A readAttributes(Path path, Class<A> type, LinkOption... options);

// 读取类型为 A 的文件属性

static DirectoryStream<Path> newDirectoryStream(Path path);

static DirectoryStream<Path> newDirectoryStream(Path path, String glob);

// 获取给定目录中可以遍历所有文件和目录的迭代器。第二个方法只接受那些与给定的 `glob` 模式匹配的项

static Path walkFileTree(Path start, FileVisitor<? super Path> visitor);

// 遍历给定路径的所有子孙，并将访问器应用于这些子孙上

static Stream<String> lines(Path path);

static Stream<String> lines(Path path, Charset cs);

// 产生一个流，它的元素是指定文件中的行，该文件中字符集为 `UTF-8`，或为指定的字符集

static DirectoryStream<Path> newDirectoryStream(Path path);

static DirectoryStream<Path> newDirectoryStream(Path path, String glob);

// 获取给定目录中可以遍历所有文件和目录的迭代器。第二个方法只接受那些与给定的 `glob` 模式匹配的项目

static Path walkFileTree(Path start, FileVisitor<? super Path> visitor);

// 遍历给定路径的所有子孙，并将访问器应用于这些子孙之上

```