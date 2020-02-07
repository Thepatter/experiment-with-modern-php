*java.io.FileInputStream*
```java
// * `FileInputStream` 类继承自 `InputStream` 类，用于从文件读取字节

FileInputStream(File file);

// 从一个 `File` 对象创建一个 `FileInputStream`

FileInputStream(String filename);

// 从一个文件名创建一个 `FileInputStream` 如果试图为一个不存在的文件创建 FileInputStream 对象，将会发生 java.io.FileNotFoundException 异常。非绝对的路径名将按照相对于 VM 启动时所设置的工作目录来解析

FileChannel getChannel();

// 返回用于访问这个输入流的通道

```