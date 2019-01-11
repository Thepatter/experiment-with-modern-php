## Java SE 8 的流操作相关

### 流概述

流提供了一种比集合更高的概念级别上指定计算的数据视图。用来以“做什么而非怎么做”的方式处理集合

流与集合很类似，都可以让我们转换和获取数据，它们之间的差异为

1.流并不存储其元素。这些元素可能存储在底层的集合中，或者是按需生成的

2.流的操作不会修改其数据源。（`filter` 方法不会从新的流中移出元素，而是会生成一个新的流，其中不包含被过滤掉的元素）

3.流的操作是尽可能惰性执行。即：直至需要其结果时，操作才会执行。

```java
long count = words.parallelStream()
                .filter(w -> w.length() > 12)
                .count();
```

上述示例中，`stream` 和 `parallelStream` 方法会产生一个用于 `words` 列表的 `stream` 。`filter` 方法会返回另一个流，其中只包含长度大于 12 的单词。`count` 方法会将这个流简化为一个结果。这个工作流是操作流时的典型流程。建立了一个包含三个阶段的操作管道

1.创建一个流

2.指定将初始流转换为其他流的中间操作，可能包含多个步骤

3.应用终止操作，从而产生结果。这个操作会强制执行之前的惰性操作。从此之后，这个流就再也不能用了

### 流的创建

* `Collection` 接口的 `stream` 方法将任何集合转换为一个流

* `Stream.of` 的静态方法可以从数组创建一个流， `of` 方法具有可变长参数，可以构建具有任意数量引元的流

* `Array.stream(array, from, to)` 可以从数组中位于 `from`(包括) 和 `to` (不包括) 的元素中创建一个流

* `Stream.empty()` 方法创建不包含任何元素的流

* `Stream` 接口有两个用于创建无限流的静态方法：`generate()`，`iterate()` 方法

    `generate()` 方法会接受一个不包含任何引元的函数（是一个 `Supplier<T>` 接口的对象）。只要需要一个流类型的值，该函数就会被调用以产生一个这样的值。

    ```java
    Stream<String> echos = Stream.generate(()-> "Echo");
    // 获取一个随机数的流
    Stream<Double> randoms = Stream.generate(Math::random);
    ```

    `iterate()` 方法可以产生无限序列，会接受一个“种子”值，以及一个函数（是一个 `UnaryOperation<T>`），并且会反复地将函数应用到之前的结果上。

    ```java
    // 该序列中的第一个元素是种子BigInteger.ZERO，第二个元素是 f(seed),即 1（作为大整数），下一个元素是 f(f(seed)) 即 2，后续以此类推
    Stream<BigInteger> integers = Stream.iterate(BigInteger.ZERO, n->n.add(BigInteger.ONE));
    ```

* `Pattern` 类的 `splitAsStream()` 方法，它会按照某个正则表达式来分割一个 `CharSequence` 对象

  ```java
  Stream<String> words = Pattern.compile("\\PL+").splitAsStream(contents);
  ```

* 静态的 `Files.lines()` 方法会返回一个包含了文件中所有行的 `Stream`

  ```java
  try (Stream<String> lines = Files.lines(path))
  {
      
  }
  ```

### 抽取子流和连接流

* 调用 `stream.limit(n)` 会返回一个新的流，它在 n 个元素之后结束（如果原来的流更短，那么就会在流结束时结束）。这个方法对于裁剪无限流的尺寸会显得特别有用。

```java
// 生成一个包含 100 个随机数的流
Stream<Double> randoms = Stream.generate(Math::random).limit(100);
```

* 调用 `stream.skip(n)` 会丢弃前 n 个元素。这个方法在文本分隔为单词时会显得很方便

*  `Stream` 类的静态的 `concat` 方法将两个流连接起来：

```java
Stream<String> combined = Stream.concat(letters("Hello"), letters("World"));
```

当然第一个流不应该是无限的，否则第二个流永远都不会得到处理的机会

### 其他流转换

*  `distinct` 方法会返回一个流，它的元素是从原有流中产生的，即原来的元素按照同样的顺序剔除重复元素后产生的。

* 对于流的排序，有多种 `sorted` 方法的变体可用。其中一种用于操作 `Comparable` 元素的流，而另一个可以接受一个 `Comparator` ，与所有的流转换一样，`sorted` 方法会产生一个新的流，它的元素是原有流中按照顺序排列的元素

  ```java
  // 字符串排序，使得最长的字符串排在最前面
  Stream<String> longestFirst = words.stream().sorted(Comparator.comparing(String::length).reversed());
  ```

* `peek()` 方法会产生另一个流，它的元素与原来流中的元素相同，但是在每次获取一个元素时，都会调用一个函数。

  ```java
  Object[] powers = Stream.iterate(1.0, p -> p * 2).peek(e -> System.out.println("Fetching " + e)).limit(20).toArray();
  ```

### 从流中获得结果

​	将流约简为可以在程序中使用的非流值。

* `count()` 方法会返回流中元素的数量

* `max()` 和 `min()` 会返回最大值和最小值。这些方法返回的是一个类型 `Optional<T>` 的值，它要么在其中包装了答案，要么表示没有任何值（因为流碰巧为空）。在过去，碰到这种情况返回 `null` 是很常见的，但是这样会导致在未做完备测试的程序中产生空指针异常。`Optional` 类型是一种更好的表示缺少值的方式

  ```java
  // 获取流中的最大值
  Optional<String> largest = words.max(String::compareToIgnoreCase);
  ```

* `findFirst()` 返回的是非空集合中的第一个值。它通常会在与 `filter()` 组合使用时显得很有用。

  ```java
  // 查找第一个以字母 Q 开头的单词
  Optional<String> startsWithQ = words.filter(s -> s.startsWith("Q")).findFirst();
  ```

* `findAny()` 方法返回任意一个它找到的匹配，在并行处理流时会很有效

  ```java
  Optional<String> startWithQ = words.filter(s -> s.startsWith("Q")).findFirst();
  ```

* 如果只想知道是否存在匹配，使用 `anyMatch` 。这个方法会接受一个断言引元，因此不需要使用 `filter`

  ```java
  boolean aWordStartsWithQ = words.parallel().anyMatch(S -> s.startsWith("Q")).findAny();
  ```

* `allMatch()` 和 `noneMatch` 方法，分别会在所有元素和没有任何元素匹配断言的情况下返回 true。这些方法也可以通过并行运行而获益

### Optional 类型

​	`Optional<T>` 对象是一种包装器对象，要么包装了类型 T 的对象，要么没有包装任何对象。对第一种情况，即值为存在的。`Optional<T>` 类型被当作一种更安全的方式，用来替代类型 T 的引用，这种引用要么引用某个对象，要么为 `null`。但是，它只有在正确使用的情况下才会更安全

​	有效使用 `Optional` 的关键字是要使用这样的方法：

* 它在值不存在的情况下会产生一个可替代物，而只有在值存在的情况下才会使用这个值

```java
// 在没有匹配时，使用某种默认值
String result = optionalString.orElse("");
// 计算默认值
String result = optionalString.orElseGet(()->Locale.getDefault().getDisplayName());
// 在没有任何值时抛出异常
String result = optionalString.orElseThrow(IllegalStateException::new);
```

* 调用 `ifPresent` 方法会接受一个函数。如果该可选值存在，那么它会被传递给该函数。否则，不会发生任何事情

  ```java
  optionalValue.ifPresent(V->Process V);
  // 如果在该值存在的情况下想要将其添加到某个集中，调用
  optionalValue.ifPresent(V->results.add(V))
  // 上面等价于
  optionalValue.ifPresent(results::add);
  ```

  当调用 `ifPresent` 时，从该函数不会返回任何值。如果想要处理函数的结果，应该使用 `map()`

  ```java
  Optional<Boolean> added = optionalValue.map(results::add);
  ```

  如果没有正确地使用 `Optional` 值，那么相比较以往得到“某物或null”的方式，并没有得到任何好处。

  `get()` 方法会在 `Optional` 值存在的情况下获得其中包装的元素，或者在不存在的情况下抛出一个 `NoSuchElementException` 对象。

  ```java
  Optional<T> optionalValue = ...;
  optionalValue.get().someMethod();
  // 并不比下面的方式更安全
  T value = ...;
  values.someMethod();
  ```

  `isPresent()` 方法会报告某个 `Optional<T>` 对象是否具有一个值。但是 

  ```java
  if (optionalValue.isPresent()) {
      optionValue.get().someMethod();
  }
  // 并不比下面的方式更容易处理
  if (value != null) {
      value.someMethod();
  }
  ```

### 收集到映射表中

假设有一个 `Stream<Person>` ，并且想要将其元素收集到一个映射表中，这样后续就可以通过它们的 ID 来查找人员了。`Collectors.toMap()` 方法有两个函数引元，它们用来产生映射表的键和值

```java
Map<Integer, Persion> idToName = people.collect(Collectors.toMap(Person::getId, Function.identity()));
```

如果有多个元素具有相同的键，那么就会存在冲突，收集器将会抛出一个 `IllegalStateException` 对象。可以通过提供第 3 个函数引元来覆盖这种行为，该函数会针对给定的已有值和新值来解决冲突并确定键对应的值。这个函数应该返回已有值，新值或它们的组合

### 下游收集器

`groupingBy` 方法会产生一个映射表，它的每个值都是一个列表。如果想要以某种方式来处理这些列表，就需要提供一个“下游收集器”。如果想要获得集而不是列表，那么可以使用 `Collector.toSet` 收集器

```java
Map<String, Set<Local>> countryToLocaleSet = locales.collect(groupingBy(Locale::getCountry, toSet()));
```

Java 提供了多种可以将群组元素简约为数字的收集器

* `counting` 会产生收集到的元素的个数

  ```java
  // 对每个国际有多少个 Locale 进行计数
  Map<String, Long> countryToLocaleCounts = locales.collect(groupingBy(Locale::getCountry, counting()));
  ```

* `summing(Int|Long|Double)` 会接受一个函数作为引元，将该函数应用到下游元素中，并产生它们的和。

  ```java
  // 可以计算城市流中每个州的人口总和
  Map<String, Integer> stateToCityPopulation = cities.collect(groupingBy(City::getState, summingInt(City::getPopulation)));
  ```

* `maxBy` 和 `minBy` 会接受一个比较器，并产生下游元素中的最大值和最小值

  ```java
  // 产生每个州中最大的城市
  Map<String, Optional<City>> stateToLargestCity = cities.collect(groupingBy(City::getState, maxBy(Comparator.comparing(City::getPopulation))));
  ```

  `mapping` 方法会产生将函数应用到下游结果上的收集器，并将函数值传递给另一个收集器

  ```java
  // 按照州将城市群组在一起，在每个州内部，生成了各个城市的名字，并按照最大长度约简
  Map<String, Optional<String>> stateToLongestCityName = cities.collect(
  	groupingBy(City::getState,
  		mapping(City::getName,
  			maxBy(Comparator.comparing(String::length))
          )
  	)
  );
  ```

  将收集器组合起来是一种很强大的方式，但是它也可能会导致产生非常复杂的表达式。最佳用法是与 `groupingBy` 和 `partitioningBy` 一起处理下游的映射表中的值。否则，应该直接在流上应用诸如 `map`，`reduce`，`count`，`max`，`min` 这样的方法

### 约简操作

`reduce` 方法是一种用于从流中计算某个值的通用机制，其最简单的形式将接受一个二元函数，并从前两个元素开始持续应用它。

```java
List<Integer> values = ....;
// reduce 方法计算 v0+v1+v2+...，其中 v1 是流中的元素。如果流为空，返回一个 optional
Optional<Integer> sum = values.stream().reduce((x, y) -> x + y);
```

另一种形式的 `reduce`，如果流为空，则返回幺元值，这样则无需处理 `Optional` 类

```java
List<Integer> values = ...;
Integer sum = values.stream().reduce(0, (x, y) -> x + y);
```

假设有一个对象流，并且想要对某些属性求和，例如字符串流中的所有字符串的长度，那么你就不能使用简单形式的 `reduce`，而是需要 `(T，T)->T` 这样的函数，即引元和结果的类型相同的函数。但是在这种情况下，你有两种类型：流的元素具有 `String` 类型，而累积结果是整数。有一种形式的 `reduce` 可以处理这种情况。首选，提供一种"累积器"函数`(total, word) -> total + word.length()`。这个函数会被反复调用，产生累积的总和。但是，当计算被并行化时，会有多个这种类型的计算，需要将它们的结果合并。因此，需要提供第二个函数来执行此处理。完整的调用如下：

```java
int result = words.reduce(0, (total, word) -> total + word.length(),
	(total1, total2) -> total1 + total2);
)
```

### 基本类型流

将整数收集到 `Stream<Integer>` 中，将每个整数都包装到包装器对象中是很低效的。对其他基本类型来说，情况也是一样。流库中具有专门的类型 `IntStream`，`LongStream` 和 `DoubleStream` ，用来直接存储基本类型值，而无需使用包装器。如果想要存储 `short`、`char`、`byte`、`boolean`，可以使用 `IntStream`，而对于 `float`，可以使用 `DoubleStream`

创建 `IntStream` ，需要调用 `IntStream.of` 和 `Arrays.stream` 方法

```java
IntStream stream = IntStream.of(1, 1, 2, 3, 5);
stream = Arrays.stream(values, from, to); // values is an int[] array
```

与对象流一样，可以使用静态 `generate` 和 `iterate` 方法。`IntStream` 和 `LongStream` 有静态方法 `range` 和 `rangeClosed`，可以生成步长为 1 的整数范围

```java
// 上限被排除在外
IntStream zeroToNinetyNine = IntStream.range(0, 100);
// 包含上限
IntStream zeroToHundred = InStream.rangeClosed(0, 100);
```

`CharSequence` 接口拥有 `codePoints` 和 `chars` 方法，可以生成由字符的 `Unicode` 码或 `UTF-16` 编码机制的码元构成的 `IntStream` 

```java
String sentence = "\uD835\uDD46 is the set of  octonions."
IntStream codes= sentence.codePoints();
```

当你有一个对象流时，可以用 `mapToInt`、`mapToLong` 和 `mapToDouble` 将其转换为基本类型流。

```java
Stream<String> words = ...;
// 将其长度处理为整数
IntStream lengths = words.mapToInt(String::length);
```

使用 `boxed` 方法，将基本类型流转换为对象流

```java
Stream<Integer> integers = IntStream.range(0, 100).boxed();
```

通常，基本类型流上的方法与对象流上的方法类似。主要的差异是

`toArray` 方法会返回基本类型数组

产生可选结果的方法会返回一个 `OptionalInt`，`OptionalLong`，`OptionalDouble` 这些类与 `Optional` 类类似，但是具有 `getAsInt`，`getAsLong` 和 `getAsDouble` 方法，而不是 `get` 方法

具有返回总和、平均值、最大值、最小值的 `sum`、`average`、`max`、`min` 方法。对象流没有定义这些方法

`summaryStatistics` 方法会产生一个类型为 `IntSummaryStatistics`、`LongSummaryStatistics`、`DoubleSummaryStatistics` 的对象，它们可以同时报告流的总和、平均值、最大值和最小值

`Random` 类具有 `ints`、`longs` 和 `doubles` 方法，它们会返回由随机数构成的基本类型流

### 并行流

流使得并行处理块操作变的很容易。这个过程几乎是自动的，但是需要遵守一些规则。首先必须有一个并行流。可以使用 `Collection.parallelStream()` 方法从任何集合中获取一个并行流

```java
Stream<String> parallelWords = words.parallelStream();
```

而且，`parallel` 方法可以将任意的顺序流转为并行流

```java
Stream<String> parallelWords = Stream.of(wordArray).paralle();
```

只要在终结方法执行时，流处于并行模式，那么所有的中间流操作都将被并行化

当流操作并行运行时，其目标是要让其返回结果与顺序执行时返回的结果相同。重要的是，这些操作可以是任意顺序执行。

要确保传递给并行流操作的任何函数都可以安全地并行执行，达到这个目的的最佳方式是远离易变状态。传递给并行流操作的函数不应该被堵塞。并行流使用 `fork-join` 池来操作流的各个部分。如果多个流操作被阻塞，那么池可能就无法做任何事情了

默认情况下，从有序集合（数组和列表）、范围、生成器和迭代产生的流，或者通过调用 `Stream.sorted` 产生的流，都是有序的。它们的结果是按照原来元素的顺序累积的，因此是完全可预知的。如果运行相同的操作两次，将会得到完全相同的结果

排序并不排斥高效的并行处理，当放弃排序需求时，有些操作可以被更有效地并行化。通过在流上调用 `unordered` 方法，就可以明确表示对排序不敢兴趣，在有序的流中，`distinct` 会保留所有相同元素中的第一个，这对并行化是一种阻碍，因为处理每个部分的线程在其之前的所有部分都被处理完之前，并不知道应该丢弃那些元素。如果可以接受保留唯一元素中任意一个的做法，那么所有部分就可以并行地处理（使用共享的集来跟踪重复元素），可以通过放弃排序要求来提高 `limit` 方法的速度。如果只想从流中取出任意 `n` 个元素，而并不在意到底要获取那些，可以调用

```java
Stream<String> sample = words.parallelStream().unordered().limit(n)
```

合并映射表的代价很高昂。`Collectors.groupByConcurrent` 方法使用了共享的并发映射表。映射表中值的顺序不会与流中的顺序相同

不要修改在执行某项流操作后会将元素返回到流中的集合（即使这种修改是线程安全的）流并不会收集它们的数据，数据总是在单独的集合中。如果修改了这也的集合，那么流操作的结果就是未定义的。因为中间的流操作都是惰性的，所以直到执行终结操作时才对集合进行修改仍旧是可行的。

为了让并行流正常工作，需要满足以下条件

* 数据应该再内存中。必须等到数据到达时非常低效的
* 流应该可以被高效地分成若干个子部分。由数组或平衡二叉树支撑的流都可以工作得很好，但是 `Stream.iterate` 返回的结果不行
* 流操作的工作量应该具有较大的规模。如果总工作负载并不是很大，那么搭建并行计算时所付出的代价就没有什么意义
* 流操作不应该被阻塞

即不要将所有的流都转换为并行流，只有在对已经位于内存中的数据执行大量计算操作时，才应该使用并行流