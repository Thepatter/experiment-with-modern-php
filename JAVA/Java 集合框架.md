## Java 集合框架

**Java集合框架主要分为两类：第一类是按照单个元素存储的 `Collection`，在继承树中 Set 和 List 都实现了 `Collection` 接口；第二类是按照 `key-Value` 存储的 Map**

### List 集合

List 集合是线性数据结构的主要实现，集合元素通常存储明确的上一个和下一个元素，也存在明确的第一个元素和最后一个元素。List 集合的遍历结果是稳定的。该体系最常用的是 `ArrayList` 和 `LinkedList` 两个集合类

`ArrayList` 是容量可以改变的非线程安全集合。内部实现使用数组进行存储，集合扩容时创建更大的数组空间，把原有数据复制到新数组中。`ArrayList` 支持对元素的快速随机访问，但是插入与删除时速度通常很慢，因为这个过程很有可能需要移动其他元素

`LinkedList` 本质时双向链表。与 `ArrayList` 相比，`LinkedList` 的插入和删除速度更快，但是随机访问速度则很慢。万级数据的随机访问与 `ArrayList` 性能差距巨大。除继承 `AbstractList` 抽象类外，`LinkedList` 还实现了 `Deque` (这个接口同时具有队列和栈的性质）`LinkedList` 包含 3 个重要成员：`size`、`first`、`last` `size` 时双向链表节点的个数，`first` 和 `last` 指向第一个和最后一个节点的引用。`LinkedList` 的优点在于可以将零散的内存单元通过附加引用的方式关联起来，形成按链路顺序查找的线性结构，内存利用率较高。

### Queue 集合

Queue 是一种先进先出的数据结构，队列是一种特殊的线性表，它只允许在表的一端进行获取操作，在表的另一端进行插入操作。当队列中没有元素时，称为空队列。

### Map 集合

Map 集合时以 `Key-Value` 键值对作为存储元素实现的哈希结构，`key` 是按某种哈希函数计算后是唯一的，`Value` 则是可以重复的。`HashMap` 是线程不安全的，`ConcurrentHashMap` 是线程安全的，在多线程并发场景中，优先推荐使用 `ConcurrentHashMap` ，`TreeMap` 是 `Key` 有序的 `Map` 类集合。而 `Hashtable` 因为性能已经逐渐淘汰

### Set 集合

Set 是不允许出现重复元素的集合类型。`Set` 体系最常用的是 `HashSet`、`TreeSet`、`LinkedHashSet` 三个集合类。`HashSet` 底层是 `HashMap` 来实现的，只是 `Value` 固定为一个静态对象，使用 `Key` 保证集合元素的唯一性，但不保证顺序。`TreeSet` 使用 `TreeMap` 来实现，底层为树结构，在添加新元素到集合中时，按照某种比较规则将其插入合适的位置，保证插入后的集合仍然是有序的。`LinkedHashSet` 继承自 `HashSet` ，内部使用链表维护元素插入顺序

