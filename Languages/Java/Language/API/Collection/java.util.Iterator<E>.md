*java.util.Iterator<E>*
```java
boolean hasNext();

// 如果存在可访问的元素，返回 true

E next();

// 返回将要访问的下一个对象。如果已经到达了集合的尾部，将抛出一个 `NoSuchElementException`

void remove();

// 删除上次访问的对象。这个方法必须紧跟在访问一个元素之后执行。如果上次访问之后，集合已经发生了变化，这个方法将抛出一个 `IllegalStateException`

```