*java.util.IntSummaryStatistics*

```java
// 产生汇总后的元素的个数
long getCount();
// 产生汇总后的元素的总和
int getSum();
// 产生汇总后平均值
double getAverage();
// 产生汇总后的元素的最大值,在没有任何元素时，产生 Integer.MAX_VALUE
int getMax();
// 产生汇总后的元素的最小值,在没有任何元素时，产生 Integer.MIN_VALUE
int getMin();
```