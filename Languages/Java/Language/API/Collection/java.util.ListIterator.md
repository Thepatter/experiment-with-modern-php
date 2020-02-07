*java.util.ListIterator*
```java
void add(E newElement);

// 在当前位置前添加一个元素

void set(E newElement);

// 用新元素取代 `next` 或 `previous` 上次访问的元素。如果在 `next` 或 `previous` 上次调用之后列表结构被修改了，将抛出一个 `IllegalStateException` 异常

boolean hasPrevious();

// 当反向迭代列表时，还有可供访问的元素，返回 true

E previous();

// 返回前一个对象。如果已经到达了列表的头部，就抛出一个 `NoSuchElementException` 异常

int nextIndex();

// 返回下一次调用 `next` 方法时将返回的元素索引

int previousIndex();

// 返回下一次调用 `previous` 方法时将返回的元素索引

```