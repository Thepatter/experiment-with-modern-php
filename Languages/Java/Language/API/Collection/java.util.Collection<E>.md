*java.util.Collection<E>*
```java
boolean add(Object element);

// 将一个元素添加到集合中。如果由于这个调用改变了集合，返回 true

boolean addAll(Collection<? extends E> other);

// 将 other 集合中的所有元素添加到这个集合。如果由于这个调用改变了集合，返回 true

void clear();

// 从这个集合中删除所有的元素

boolean contains(Object obj);

// 如果集合中包含了一个与 `Obj` 相等的对象，返回 true

boolean containsAll(Collection<?> other);

// 如果这个集合包含 other 集合中的所有元素，返回 true

boolean equals(o: Object);

// 如果该合集等同于另外一个合集 o,则返回 true

int hashCode();

// 返回该合集的哈希码

boolean isEmpty();

// 如果该合集没有元素，则返回 true

boolean remove(Object obj);

// 从这个集合中删除等于 obj 的对象。如果有匹配的对象被删除，返回 true

boolean removeAll(Collection<?> other);

// 从这个集合中删除 other 集合中存在的所有元素。如果由于这个调用改变了集合，返回 true

default boolean removeIf(Predicate<? super E> filter);

// 从这个集合删除 `filter` 返回 true 的所有元素。如果这个调用改变了集合，则返回 true

default void replaceAll(UnaryOperator<E> op);

// 对这个列表的所有元素应用这个操作

default boolean removeIf(Predicate<? super E> filter);

// 删除所有匹配的元素

default boolean removeIf(Predicate<? super E> filter);

// 从这个集合删除 `filter` 返回 true 的所有元素。如果由于这个调用改变了集合，则返回 true

boolean retainAll(Collection<?> other);

// 从这个集合中删除所有与 other 集合中的元素不同的元素。如果由于这个调用改变了集合，返回 true

int size();

// 返回当前存储在集合中的元素个数

Object[] toArray();

// 返回这个集合的对象数组

Iterator<E> iterator();

// 返回一个用于访问集合中每个元素的迭代器

T> T[] toArray(T[] arrayToFill);

// 返回这个集合的对象数组。如果 `arrayToFill` 足够大，就将集合中的元素填入这个数组中。剩余空间填补 null；否则，分配一个新数组，其成员类型与 `arrayToFill` 的成员类型相同，其长度等于集合的大小，并填充集合元素

default Stream<E> stream();

default Stream<E> parallelStream();

// 产生当前集合中所有元素的顺序流或并行流

```