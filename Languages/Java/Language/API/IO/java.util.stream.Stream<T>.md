*java.util.stream.Stream<T>*
```java
Stream<T> filter(Predicate<? super T> p);

// 产生一个流，其中包含当前流中满足 P 的所有元素

long count();

// 产生当前流中元素的数量。这是一个终止操作

static <T> Stream<T> of(T... values);

// 产生一个元素为给定值的流

static <T> Stream<T> empty();

// 产生一个不包含任何元素的流

static <T> Stream<T> generate(Supplier<T> s);

// 产生一个无限流，它的值是通过反复调用函数 s 而构建的

static <T> Stream<T> iterate(T seed, UnaryOperator<T> f);

// 产生一个无限流，它的元素包含种子，在种子上调用 f 产生的值，在前一个元素上调用 f 产生的值等

Stream<T> filter(Predicate<? super T> predicate);

// 产生一个流，它包含当前流中所有满足断言条件的元素

R> Stream<R> map(Function<? super T, ? extends R> mapper);

// 产生一个流，它包含将 `mapper` 应用于当前流中所有元素所产生的结果

R> Stream<R> flatMap(Function<? super T, ? extends Stream<? extends R>> mapper);

// 产生一个流，它是通过将 `mapper` 应用于当前流中所有元素所产生的结果连接到一起而获得的（这里的每个结果都是一个流）

Stream<T> limit(long maxSize);

// 产生一个流，其中包含了当前流中最初的 `maxSize` 个元素

Stream<T> skip(long n);

// 产生一个流，它的元素是当前流中除了前 n 个元素之外的所有元素

static <T> Stream<T> concat(Stream<? extends T> a, Stream<? extends T> b);

// 产生一个流，它的元素是 a 的元素后面跟着 b 的元素

Stream<T> distinct();

// 产生一个流，包含当前流中所有不同的元素

Stream<T> sorted();

Stream<T> sorted(Comparator<? super T> comparator);

// 产生一个流，它的元素是当前流中的所有元素按照顺序排列的。第一个方法要求元素是实现了 `Comparable` 的类的实例

Stream<T> peek(Consumer<? super T> action);

// 产生一个流，它与当前流中的元素相同，在获取其中每个元素时，会将其传递给 `action`

Optional<T> max(Comparator<? super T> comparator);

Optional<T> min(Comparator<? super T> comparator);

// 分别产生这个流的最大元素和最小元素，使用由给定比较器定义的排序规则，如果这个流为空，会产生一个空的 `Optional` 对象。这些操作都是终结操作

Optional<T> findFirst();

Optional<T> findAny();

// 分别产生这个流的第一个和任意一个元素，如果这个流为空，会产生一个空的 `Optional` 对象。这些操作是终结操作

boolean anyMatch(Predicate<? super T> predicate);

boolean allMatch(Predicate<? super T> predicate);

boolean noneMatch(Predicate<? super T> predicate);

// 分别在这个流中任意元素、所有元素和没有任何元素匹配给定断言时返回 `true`。这些操作都是终结操作

void forEach(Consumer<? super T> action);

// 在流的每个元素上调用 action。这是一种终结操作

Object[] toArray();

A> A[] toArray(IntFunction<A[]> generator);

// 产生一个对象数组，或者在将引用 `A[]::new` 传递给构造器时，返回一个 A 类型的数组。这些操作都是终结操作

R, A> R collection(Collector<? super T,A,R> collector);

// 使用给定的收集器来收集当前流中的元素。`Collectors` 类有用于多种收集器的工厂方法

Optional<T> reduce(BinaryOperator<T> accumulator);

T reduct(T identity, BinaryOperator<T> accumulator, BinaryOperator<U> combiner);

// 用给定的 `accumulator` 函数产生流中元素的累积总和。如果提供了幺元，那么第一个被累计的元素就是该幺元。如果提供了组合器，那么它可以用来将分别累积的各个部分整合成总和

R> R collect(Supplier<R> supplier, BiConsumer<R, ? super T> accumulator, BiConsumer<R, R> combiner);

// 将元素收集到类型 R 的结果中。在每个部分上，都会调用 `supplier` 来提供初始结果，调用 `accumulator` 来交替地将元素添加到结果中，并调用 `combiner` 来整合两个结果

```