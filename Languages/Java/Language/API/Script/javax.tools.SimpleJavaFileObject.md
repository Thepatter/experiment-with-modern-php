*javax.tools.SimpleJavaFileObject*
```java
CharSequence getCharContent(boolean ignoreEncodingErrors);

// 对于表示源文件并产生源代码的文件对象，需要覆盖该方法

OutputStream openOutputStream();

// 对于表示类文件并产生字节码可写入其中的流的文件对象，需要覆盖该方法

```