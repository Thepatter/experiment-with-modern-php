*java.util.Random*

```java
// 以当前时间作为种子创建一个 Random 对象
Random();
// 以 seed 值为种子创建 Random
Random(long seed);
// 返回一个随机值
int nextInt();
long nextLong();
// 不包含 1.0
double nextDouble();
float nextFloat();
boolean nextBoolean();
// 返回一个 0 到 n - 1 之间的随机数
int nextInt(int n);
// 产生随机数流。如果提供了 streamSize，这个流就是具有给定数量元素的有限流。当提供了边界时，其元素将位于 randomNumberOrigin (包含) 和 randomNumberDound (不包含) 的区间内
IntStream ints();
IntStream ints(int randomNumberOrigin, int randomNumberBound);
IntStream ints(long streamSize);
IntStream ints(long streamSize, int randomNumberOrigin, int randomNumberBound);
LongStream longs();
LongStream longs(long randomNumberOrigin, long randomNumberBound);
LongStream longs(long streamSize);
LongStream longs(long streamSize, long randomNumberOrigin, long randomNumberBound);
DoubleStream doubles();
DoubleStream doubles(double randomNumberOrigin, double randomNumberBound);
DoubleStream doubles(long streamSize);
DoubleStream doubles(long streamSize, double randomNumberOrigin, double randomNumberBound);
```