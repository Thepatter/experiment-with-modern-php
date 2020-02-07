*java.nio.channels.FileChannel*
```java
static FileChannel open(Path path, OpenOption... options);

// 打开指定路径的文件通道，默认情况下，通道打开时用于读入

// `path`                        打开通道的文件所在的路径

// `options`   `StandardOpenOption` 枚举中的 `WRITE`、`APPEND`、`TRUNCATE_EXISTING`、`CREATE` 值

MappedByteBuffer map(FileChannel.MapMode mode, long position, long size);

// 将文件的一个区域映射到内存中

// `mode`            `FileChannel.MapMode` 类中的常量 `READ_ONLY`、`READ_WRITE`、`PRIVATE` 之一

// `position`         映射区域的起始位置

// `size`                 映射区域的大小

FileLock lock();

// 在整个文件上获得一个独占锁，这个方法将阻塞直至获得锁

FileLock tryLock();

// 在整个文件上获得一个独占锁，或者在无法获得锁的情况下返回 null

FileLock  lock(long position, long size, boolean shared);

FileLock tryLock(long position, long size, boolean shared);

// 在文件的一个区域上获得锁，第一个方法将阻塞直至获得锁，而第二个方法将在无法获得锁时返回 null

// `position`			要锁定区域的起始位置

// `size`				要锁定区域的尺寸

// `shared`				`true` 为共享锁，false 为独占锁

```