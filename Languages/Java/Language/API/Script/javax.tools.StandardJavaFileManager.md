*javax.tools.StandardJavaFileManager*
```java
Iterable<? extends JavaFileObject> getJavaFileObjectsFromStrings(Iterable<String> fileNames);

Iterable<? extends JavaFileObject> getJavaFileObjectsFromFiles(Iterable<? extends File> files);

// 将文件名或文件序列转译成一个 `JavaFileObject` 实例序列

```