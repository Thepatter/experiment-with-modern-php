*java.util.stream.Collectors*
```java
static <T> Collector<T, ?, List<T>> toList();

static <T> Collector<T, ?, Set<T>> toSet();

// 产生一个将元素收集到列表或集中的收集器

static <T, K, U> Collector<T, ?, Map<K, U>> toMap(Function<? super T, ? extends K> keyMapper, Function<? super T, ? extends U valueMapper);

static <T, K, U> Collector<T, ?, Map<K, U>> toMap(Function<? super T, ? extends k> keyMapper, Function<? super T, ? extends U> valueMapper, BinaryOperator<U> mergeFunction);

static <T, K, U, M extends Map<K, U>> Collector<T, ?, M> toMap(Function<? super T, ? extends K> keyMapper, Function<? super T, ? extends U> valueMapper, BinaryOperator<U> mergeFunction, Supplier<M> mapSupplier);

static <T, K, U> Collector<T, ?, ConcurrentMap<K, U>> toConcurrentMap(Function<? super T, ? extends K> keyMapper, Function<? super T, ? extends U> valueMapper);

static <T, K, U> Collector<T, ?, ConcurrentMap<K, U>> toConcurrentMap(Function<? super T, ? extends K> keyMapper, Function<? super T, ? extends U> valueMapper, BinaryOperator<U> mergeFunction);

static <T, K, U, M extends ConcurrentMap<K, U>> Collector<T, ?, M> toConcurrentMap(Function<? super T, ? extends K> keyMapper, Function<? super T, ? extends U> valueMapper, BinaryOperator<U> mergeFunction, Supplier<M> mapSupplier);

// 产生一个收集器，它会产生一个映射表或并发映射表。`keyMapper` 和 `valueMapper` 函数会应用于每个收集到的元素上，从而在所产生的映射表中生成一个键/值项。默认情况下，当两个元素产生相同的键时，会抛出一个 `IllegalStateException` 异常。可以提供一个 `mergeFunction` 来合并具有相同键的值。默认情况下，其结果是一个 `HashMap` 或 `ConcurrentHashMap`。可以提供一个 `mapSupplier`,它会产生所期望的映射表实例

static <T, C extends Collection<T>> Collector<T, ?, C> toCollection(Supplier<C> collectionFactory);

// 产生一个将元素收集到任意集合中的收集器。可以传递一个诸如 `TreeSet::new` 的构造器引用

static Collector<CharSequence, ?, String> joining();

static Collector<CharSequence, ?, String> joining(CharSequence delimiter);

static Collector<CharSequence, ? String> joining(CharSequence delimiter, CharSequence prefix, CharSequence suffix);

// 产生一个连接字符串的收集器。分隔符会置于字符串之间，而第一个字符串之前可以有前缀，最后一个字符串之后可以有后缀。如果没有指定，那么它们都为空

static <T> Collector<T, ?, IntSummaryStatistics> summarizingInt(ToIntFunction<? super T> mapper);

static <T> Collector<T, ?, LongSummaryStatistics> summarizingLong(ToLongFunction<? super T> mapper);

static <T> Collector<T, ?, DoubleSummaryStatistics> summarizingDouble(ToDoubleFunction<? super T> mapper);

// 产生能够生成（`Int|Long|Double）SummaryStatistics` 对象的收集器，通过它可以获得将 `mapper` 应用于每个元素后所产生的结果的个数，总和，平均值，最大值和最小值

static <T, K> Collector<T, ?, Map<K, List<T>>> groupingBy(Function<? super T, ? extends K> classifier);

static <T, K> Collector<T, ?, ConcurrentMap<K, List<T>>> groupingByConcurrent(Function<? super T, ? extends K> classifier);

// 产生一个收集器，它会产生一个映射表或并发映射表，其键是将 `classifier` 应用于所有收集到的元素上所产生的结果，而值是由具有相同键的元素构成的一个个列表

static <T> Collector<T, ?, Map<Boolean, List<T>> partitioningBy(Predicate<? super T> predicate);

// 产生一个收集器，它会产生一个映射表，其键是 `true/false`，而值是由满足/不满足断言的元素构成的列表

static <T> Collector<T, ?, Long> counting();

// 产生一个可以对收集到的元素进行计算的收集器

static <T> Collector<T, ?, Integer> summingInt(ToIntFunction<? super T> mapper);

static <T> Collector<T, ?, Long> summingLong(ToLongFunction<? super T> mapper);

static <T> Collector<T, ?, Double> summingDouble(ToDoubleFunction<? super T> mapper);

// 产生一个收集器，对将 `mapper` 应用到收集到的元素上之后产生的值计算总和

static <T> Collector<T, ?, Optional<T>> maxBy(Comparator<? super T> comparator);

static <T> Collector<T, ?, Optional<T>> minBy(Comparator<? super T> comparator);

// 产生一个收集器，使用 `comparator` 制度的排序方法，计算收集到的元素中的最大值和最小值

static <T, U, A, R> Collector<T, ?, R> mapping(Function<? super T, ? extends U> mapper, Collector<? super U, A, R> downstream);

// 产生一个收集器，它会产生一个映射表，其键是将 `mapper` 应用到收集到的数据上面产生的，其值是使用 `downstream` 收集器收集到的具有相同键的元素

```