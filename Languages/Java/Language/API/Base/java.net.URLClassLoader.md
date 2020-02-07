*java.net.URLClassLoader*
```java
URLClassLoader(URL[] urls);

URLClassLoader(URL[] urls, ClassLoader parent);

// 构建一个类加载器，它可以从给定的 `URL` 处加载类。如果 `URL` 以 `/` 结尾，那么它表示的一个目录，否则，它表示的是一个 `JAR` 文件

```