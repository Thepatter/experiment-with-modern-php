*java.io.FileOutputStream*
```java
// * `FileOutputStream` 类继承自 `OutputSteam` 类，用于向文件写入字节

FileOutputStream(String name);

FileOutputStream(String name, boolean append);

FileOutputStream(File file);

FileOutputStream(File file, boolean append);

// 使用由 `name` 字符串或 `file` 对象指定路径名的文件创建一个新的文件输出流。如果 `append` 参数为 `true`，那么数据将被添加到文件尾，而具有相同名字的已有文件不会被删除；否则，这个方法会删除所有具有相同名字的已有文件

FileChannel getChannel();

// 返回用于访问这个输出流的通道

```