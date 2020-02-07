*java.nio.file.SimpleFileVisitor<T>*
```java
static FileVisitResult visitFile(T path, BasicFileAttributes attrs);

// 在访问文件或目录时被调用，返回 `CONTINUE`，`SKIP_SUBTREE`，`SKIP_SIBLINGS`，`TERMINATE` 之一，默认实现是不做任何操作而继续访问

static FileVisitResult preVisitDirectory(T dir, BasicFileAttributes attrs);

static FileVisitResult postVisitDirectory(T dir, BasicFileAttributes attrs);

// 在访问目录之前和之后被调用，默认实现是不做任何操作而继续访问

static FileVisitResult visitFileFailed(T path, IOException exc);

// 如果在试图获取给定文件的信息时抛出异常，则该方法被调用。默认实现是冲洗抛出异常，这会导致访问操作以这个异常而终止。如果想访问，可以覆盖这个方法

```