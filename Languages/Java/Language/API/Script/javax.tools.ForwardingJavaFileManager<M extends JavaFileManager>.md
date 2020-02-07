*javax.tools.ForwardingJavaFileManager<M extends JavaFileManager>*
```java
protected ForwardingJavaFileManager(M fileManager);

// 构造一个 `JavaFileManager`，它将所有的调用都代理给指定的文件管理器

FileObjectgetFileForOutput(JavaFileManager.Locationlocation, StringclassName, JavaFileObject.kind kid, FileObject sibling);

// 如果希望替换用于写出类文件的文件对象，则需要拦截该调用。`kind` 的值是 `SOURCE`、`CLASS`、`HTML`、`OTHER` 之一

```