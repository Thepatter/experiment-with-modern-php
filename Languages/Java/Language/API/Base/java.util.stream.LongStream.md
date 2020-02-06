*java.util.stream.LongStream*

```java
// 用给定范围内的整数产生一个 LongStream，不包含右边界
static LongStream range(long startInclusive, long endExclusive);
// 用给定范围内的整数产生一个 rangeClosed(long startInclusive, long endInclusive);
static LongStream rangeClosed(long startInclusive, long endIncludsive);
// 用给定元素产生一个 LongStream
static LongStream of(long ...values);
// 用当前流中的元素产生一个数组
long[] toArray();
// 产生当前流中元素的和、平均值、最大、最小或获得这些结果的所有四种值的对象
long sum();
OptionalDouble average();
OptionalLong max();
OptionalLong min();
OptionalSummaryStatistics summaryStatistics();
// 产生用于当前流中元素的包装器对象流
Stream<Long> boxed();
```

