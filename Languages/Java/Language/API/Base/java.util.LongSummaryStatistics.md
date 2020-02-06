*java.util.LongSummaryStatistics*

```java
// 产生汇总后的元素个数
long getCount();
// 产生汇总后元素总和
long getSum();
// 产生汇总后元素均值
double getAverage();
// 产生汇总后的元素的最大值，没有任何元素时，返回 Long.MAX_VALUE
long getMax();
// 产生汇总后的元素的最小值，没有任何元素时，返回 Long.MIN_VALUE
long getMin();
```