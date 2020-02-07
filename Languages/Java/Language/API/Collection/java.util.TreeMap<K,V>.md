*java.util.TreeMap<K,V>*
```java
TreeMap();

// 为实现 `Comparable` 接口的键构造一个空的树映射

TreeMap(Comparator<? super K> c);

// 构造一个树映射，并使用一个指定的比较器对键进行排序

TreeMap(Map<? extends k, ? extends V> entries);

// 构造一个树映射，将某个有序映射中的所有条目添加到树映射中。

TreeMap(SortedMap<? extends k, ? extends V> entries);

// 构造一个树映射，将某个有序映射中的所有条目添加到树映射中，并使用与给定的有序映射相同的比较器

```