## java.util.stream.DoubleStream

* `static DoubleStream of(double ... values)`

  用给定元素产生一个 `DoubleStream`

* `double[] toArray()`

  用当前流中的元素产生一个数组

* `double sum()`

* `OptionalDouble average()`

* `OptionalDouble max()`

* `OptionalDouble min()`

* `DoubleSummaryStatistics summaryStatistics()`

  产生当前流中元素的总和、平均值、最大值和最小值，或者从中可以获得这些结果的所有四种值的对象

* `Stream<Double> boxed()`

  产生用于当前流中的元素的包装器对象流