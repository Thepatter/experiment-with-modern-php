*java.io.File*
```java
File(String pathname);

// 为一个指定的路径名创建一个 File 对象，路径名可能是一个目录或者一个文件

File(String parent, String child);

// 在目录 `parent` 下创建一个子路径的 `File` 对象，子路径可能是一个目录或者一个文件

File(File, parent, String child);

// 在目录 `parent` 下创建一个子路径的 `File` 对象，`parent` 是一个 `File` 对象

boolean canRead();

// `File` 对象代表的文件存在且可读，返回 `true`

boolean canWrite();

// `File` 对象代表的文件存在且可写，返回 `true`

boolean exists();

// `File` 对象代表的文件和目录存在，返回 `true`

boolean delete();

// 删除 `File` 对象代表的文件或目录，如果删除成功，返回 `true`

String getName();

// 返回 `File` 对象的文件名

String getPath();

// 返回完整路径和文件名

String getParent();

// 返回 `File` 对象代表的当前目录和文件的完整父目录

long lastModified();

// 返回文件最后修改时间

long lengt();

// 返回文件的大小，如果不存在或者是一个目录的话，返回 0

File[] listFile();

// 返回一个目录 `File` 对象下面的文件

boolean isDirectory();

// `File` 对象代表的是一个目录，返回 `true`

boolean isFile();

// `File` 对象代表的是一个文件，返回 `true`

boolean isAbsolute();

// 如果 `File` 对象采用绝对路径名创建，返回 `true`

boolean isHidden();

// 如果 `File` 对象代表的文件是隐藏的，返回 `true`

String getAbslutePath();

// 返回 `File` 对象代表的文件和目录的完整绝对路径名

String getCanonicalPath();

// 和 getAbsolutePath() 返回相同，除了从路径名中去掉了冗余的名字，（.和..) 以及符号链接（unix),以及将盘符转换为标准的大写形式

boolean renameTo(File dest);

// 将 `File` 对象代表的文件或者目录改名为 `dest` 中指定的名字，如果操作成功，返回 `true`

boolean mkdir();

// 创建该 `File` 对象代表的目录，如果目录创建成功，返回 `true`

boolean mkdirs();

// 和 `mkdir()` 相同，除开在父目录不存在的情况下，将和父目录一起创建

Path toPath();

// 从该文件中创建一个 `Path` 对象

```