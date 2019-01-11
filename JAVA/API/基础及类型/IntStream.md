## java.util.stream.IntStream

* `static IntStream range(int startInclusive, int endExclusive)`

* `static IntStream rangeClosed(int startInclusive, int endIncludsive)`

  产生一个由给定范围内的整数构成的 `IntStram`。第一个方法不包含后面边界，第二个包含后面的边界

* `static IntStream of(int ... values)`

  产生一个由给定元素构成的 `IntStream`

* `int[] toArray()`

  产生一个由当前流中的元素构成的数组

* `int sum()`

* `OptionalDouble average()`

* `OptionalInt max()`

* `OptionalInt min(0)`

* `IntSumarayStatistics summaryStatistics()`

  产生当前流中元素的总和，平均值、最大值和最小值，或者从中可以获得这些结果的所有四种值的对象

* `Stream<Integer> boxed()`

  产生用于当前流中的元素的包装器对象流

