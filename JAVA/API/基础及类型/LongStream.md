## java.util.stream.LongStream

* `static LongStream range(long startInclusive, long endExclusive)`

* `static LongStream rangeClosed(long startInclusive, long endInclusive)`

  用给定范围内的整数产生一个 `LongStream`

* `static LongStream of(long ... values)`

  用给定元素产生一个 `LongStream`

* `long[] toArray()`

  用当前流中的元素产生一个数组

* `long sum()`

* `OptionalDouble average()`

* `OptionalLong max()`

* `OptionalLong min()`

* `LongSummaryStatistics summaryStatistics()`

  产生当前流中元素的总和、平均值、最大值和最小值，或者从中可以获得这些结果的所有四种值的对象

* `Stream<Long> boxed()`

  产生用于当前流中元素的包装器对象流