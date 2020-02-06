*java.util.stream.IntStream*

```java
// 产生一个给定范围内的整数构成的 IntStream 不包含后面边界
static IntStream range(int startInclusive, int endExclusive);
// 产生一个给定范围内的整数构成的 IntStream 包含后面边界
static IntStream rangeClosed(int startInclusive, int endIncludsive);
// 产生一个由给定元素构成的 IntStream
static IntStream of(int ...values);
// 产生当前流中的元素构成的 IntStream
int[] toArray();
// 产生当前流中元素的和，均值，最大，最小，或这些结果的对象
int sum();
OptionalDouble average();
OptionalInt max();
OptionalInt min();
IntSumarayStatistics summaryStatistics();
// 产生用于当前流中的元素的包装器对象流
Stream<Integer> boxed();
```

