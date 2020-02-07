*java.util.HashSet<E>*
```java
HashSet();

// 构造一个空的散列表

HashSet(Collection<? extends E> elements);

// 构造一个散列集，并将集合中的所有元素添加到这个散列集中

HashSet(int initialCapacity);

// 构造一个空的具有指定容量（桶数）的散列集

HashSet(int initialCapacity, float loadFactor);

// 构造一个具有指定容量和装填因子（一个0.0 ~ 1.0 之间的数值，确定散列表填充的百分比，当大于这个百分比时，散列表进行再散列）的空散列集

```