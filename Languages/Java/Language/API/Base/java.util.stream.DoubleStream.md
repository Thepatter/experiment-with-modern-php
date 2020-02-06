*java.util.stream.DoubleStream*

```java
// 用给定元素产生一个 DoubleStream
static DoubleStream of(double ... values);
// 用当前流中元素产生一个数组
double[] toArray();
// 当前流中元素和
double sum();
// 当前流中元素平均值
OptionalDouble average();
// 当前流中元素最大
OptionalDouble max();
// 当前流中元素最小
OptionalDouble min();
// 获取和、平均、最大、最小 对象
DoubleSummaryStatistics summaryStatistics();
// 用于当前流中的元素的包装器对象流
Stream<Double> boxed();
```