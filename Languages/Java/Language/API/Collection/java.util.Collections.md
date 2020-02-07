*java.util.Collections*
```java
static <E> Collection unmodifiableCollection(Collection<E> c);

static <E> List unmodifiableList(List<E> e);

static <E> Set unmodifiableSet(Set<E> c);

static <E> SortedSet unmodifiableSortedSet(SortedSet<E> c);

static <E> SortedSet unmodifiableNavigableSet(NavigableSet<E> c);

static <K, V> Map unmodifiableMap(Map<K, V> c);

static <K, V> SortedMap unmodifiableSortedMap(SortedMap<K, V> c);

static <K, V> SortedMap unmodifiableNavigableMap(NavigableMap<K, V> c);

// 构造一个集合视图：视图的更改器方法抛出一个 `UnsupportedOperationException`

static <E> Collection<E> synchronizedCollection(Collection<E> c);

static <E> List synchronizedList(List<E> c);

static <E> Set synchronizedSet(Set<E> c);

static <E> SortedSet synchronizedSortedSet(SortedSet<E> c);

static <E> NavigableSet synchronizedNavigableSet(NavigableSet<E> c);

static <K, V> Map<K, V> synchronizedMap(Map<K, V> c);

static <K, V> SortedMap<K, V> synchronizedSortedMap(SortedMap<K, V> c);

static <K, V> NavigableMap<K, V> synchronizedNavigableMap(NavigableMap<K, V> c);

// 构造一个集合视图；视图的方法同步

static <E> Collection checkedCollection(Collection<E> c, Class<E> elementType);

static <E> List checkedList(List<E> c, Class<E> elementType);

static <E> Set checkedSet(Set<E> c, Class<E> elementType);

static <E> SortedSet checkedSortedSet(SortedSet<E> c, Class<E> elementType);

static <E> NavigableSet checkedNavigableSet(NavigableSet<E> c, Class<E> elementType);

static <k, V> Map checkedMap(Map<K, V> c, Class<K> keyType, Class<V> valueType);

static <k, v> SortedMap checkedSortedMap(SortedMap<K, V> c, Class<K> keyType, Class<V> valueType);

static <K, V> NavigableMap checkedNavigableMap(NavigableMap<K, V>c, Class<K> keyType, Class<V> valueType);

static <E> Queue<E> checkedQueue(Queue<E> queue, Class<E> elementType);

// 构造一个集合视图；如果插入一个错误类型的元素，视图的方法抛出一个 `ClassCastException`

static <E> List<E> nCopies(int n, E value);

static <E> Set<E> singleton(E value);

static <E> List<E> singletonList(E value);

static <K, V> Map<K, V> singletonMap(K key, V value);

// * 构造一个对象视图。可以是一个包含 n 个相同元素的不可修改列表，也可以是一个单元素集、列表或映射

static <E> List<E> emptyList();

static <T> Set<T> emptySet();

static <E> SortedSet<E> emptySortedSet();

static NavigableSet<E> emptyNavigableSet();

static <K, V> Map<K, V> emptyMap();

static <K, V> SortedMap<K, V> emptySortedMap();

static <K, V> NavigableMap<K, V> emptyNavigableMap();

static <T> Enumeration<T> emptyEnumeration();

static <T> Iterator<T> emptyIterator();

static <T> ListIterator<T> emptyListIterator();

// 生成一个空集合、映射或迭代器

static <T extends Comparable<? super T>> void sort(List<T> elements);

// 使用稳定的排序算法，对列表中元素进行排序。这个算法的时间复杂度是`O(n logn)`，其中 n 为列表的长度

static void shuffle(List<?> elements);

static void shuffle(List<?> elements, Random r);

// 随机地打乱列表中的元素。这个算法的时间复杂度是 `O(n a(n))`，n 是列表的长度，`a(n)` 是访问元素的平均时间

static <T extends Comparable<? super T>> int binarySearch(List<T> elements, T key);

static <T> int binarySearch(List<T> elements, T key, Comparator<? super T> c);

// 从有序列表中搜索一个键，如果元素扩展了 `AbstracSequentialList` 类，则采用线性查找，否则将采用二分查找。这个方法将返回这个键在列表中的索引，如果在列表中不存在这个键将返回负值 i。在这种情况下，应该将这个键插入到列表索引 `i-1` 的位置上，以保持列表的有序性

static <T extends Comparable<? super T>> T min(Collection<T> elements);

static <T extends Comparable<? super T>> T max(Collection<T> elements);

static <T> min(Collection<T> elements, Comparator<? super T> c);

static <T> max(Collection<T> elements, Comparator<? super T> c);

// 返回集合中最小的或最大的元素

static <T> void copy(List<? super T> to, List<T> from);

// 将原列表中的所有元素复制到目标列表的相应位置上。目标列表的长度至少与原列表一样

static <T> void fill(List<? super T> l, T value);

// 将列表中的所有位置设置为相同的值

static <T> boolean addAll(Collection<? super T> c, T... values);

// 将所有的值添加到集合中。如果集合改变了，则返回 true

static <T> boolean replaceAll(List<T> l, T oldValue, T newValue);

// 用 `newValue` 取代所有值为 `oldValue` 的元素

static int indexOfSubList(List<?> l, List<?> s);

static int lastIndexOfSubList(List<?> l, List<?> s);

// 返回 l 中第一个或最后一个等于 s 子列表的索引。如果 l 中不存在等于 s 的子列表，则返回 -1

static void swap(List<?> l, int i, int j);

// 交换给定偏移量的两个元素

static void reverse(List<?> l);

// 逆置列表中元素的顺序。

static void rotate(List<?> l, int d);

// 旋转列表中的元素，将索引 i 的条目移动到位置`(i + d）% l.size()`

static int frequency(Collection<?> c, Object o);

// 返回 c 中与对象 o 相同的元素个数

boolean disjoint(Collection<?> cl, Collection<?> c2);

// 如果两个集合没有共同元素，返回 true

static <E> Collection<E> synchronizedCollection(Collection<E> c);

static <E> List synchronizedList(List<E> c);

static <E> Set synchronizedSet(Set<E> c);

static <E> SortedSet synchronizedSortedSet(SortedSet<E> c);

static <K, V> Map <K, V> synchronizedMap(Map<K, V> c);

static <K, V> SortedMap<K, V> synchronizedSortedMap(SortedMap<K, V> c);

// 构建集合视图，该集合的方法是同步的

static <T extends Comparable<? super T>> void sort(List<T> elements);

// 使用稳定的排序算法，对列表中的元素进行排序。这个算法的时间复杂度是 `O(n logn)`

```