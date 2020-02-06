*java.util.stream.BaseStream*

```java
// 产生一个用于获取当前流中各个元素的迭代器，终结操作
Iterator<T> iterator();
// 产生一个与当前流中元素相同的并行流
Stream parallel();
// 产生一个与当前流中元素相同的无序流
Stream unordered();
```

