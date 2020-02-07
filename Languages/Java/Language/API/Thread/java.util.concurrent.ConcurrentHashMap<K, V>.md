*java.util.concurrent.ConcurrentHashMap<K, V>*
```java
ConcurrentHashMap<K, V>();

ConcurrentHashMap<K, V>(int initialCapacity);

ConcurrentHashMap<K, V>(int initialCapacity, float loadFactor, int concurrencyLevel);

// 构造一个可以被多线程安全访问的散列映射表

// `initialCapacity`    集合的初始容量。默认值为 16

// `loadFactor`       控制调整：如果每一个桶的平均负载超过这个因子，表的大小会被重新调整。默认值为 0.75

// `concurrencyLevel`  并发写者线程的估计数目

ConcurrentSkipListMap<K, V>();

ConcurrentSkipListSet<K, V>(Comparator<? super k> comp);

// 构造一个可以被多线程安全访问的有序的映射表。第一个构造器要求键实现 Comparable 接口

```