*java.util.NavigableSet<E>*
```java
E higher(E value);

E lower(E value);

// 返回大于 `value` 的最小元素或小于 `value` 的最大元素，如果没有这样的元素则返回 `null`

E ceiling(E value);

E floor(E value);

// 返回大于等于 `value` 的最小元素或小于等于 `value` 的最大元素，如果没有这样的元素则返回 null

E pollFirst();

E pollLast();

// 删除并返回这个集中的最大元素或最小元素，这个集为空时返回 null

Iterator<E> descendlingIterator();

// 返回一个按照递减顺序遍历集中元素的迭代器

NavigableSet<E> subSet(E from, boolean fromIncluded, E to, boolean toIncluded);

NavigableSet<E> headSet(E to, boolean toIncluded);

NavigableSet<E> tailSet(E from, boolean fromInclued);

// 返回给定范围内的元素视图。`boolean` 标志决定视图是否包含边界

```