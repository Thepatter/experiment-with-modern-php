*java.nio.file.Path*
```java
Path resolve(Path other);

Path resolve(String other);

// 如果 `other` 是绝对路径，那么就返回 `other`；否则，返回通过连接 `this` 和 `other` 获得的路径

Path resolveSibling(Path other);

Path resolveSibling(String other);

// 如果 `other` 是绝对路径，那么就返回 `other`；否则，返回通过连接 `this` 的父路径和 `other` 获得路径

Path relativize(Path other);

// 返回同 `this` 进行解析，相对于 `other` 的相对路径

Path normalize();

// 移除诸如 `.` 和 `..` 等冗余的路径元素

Path toAbsolutePath();

// 返回与该路径等价的绝对路径

Path getParent();

// 返回父路径，或者在该路径没有父路径时，返回 null

Path getFileName();

// 返回该路径的最后一个部件，或者在该路径没有任何部件时，返回 null

Path getRoot();

// 返回该路径的根部件，或者在该路径没有任何根部件时，返回 null

toFile();

// 从该路径中创建一个 `File` 对象

```