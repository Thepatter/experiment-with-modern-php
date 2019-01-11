## java.util.Random

* `Random()`	            

  以当前时间作为种子创建一个 Random 对象

* `Random(seed: long)`		

  以一个特定值作为种子创建一个 Random 对象

* `int nextInt()`           

  返回一个随机的 int 值

* `int nextInt(int n)`     

  返回一个 0 到 n - 1 之间的随机数

* `long nextLong()`         

  返回一个随机 long 值

* `double nextDouble()`	    

  返回一个0.0 到 1.0 (不包含 1.0）之间的随机 double 类型的值

* `float nextFloat()`		

  返回一个 0.0F 到 1.0F （不包含 1.0F) 之间的随机 float 类型的值

* `boolean nextBoolean()`   

  返回一个随机的 Boolean 值

* `IntStream ints()`

* `IntStream ints(int randomNumberOrigin, int randomNumberBound)`

* `IntStream ints(long streamSize)`

* `IntStream ints(long streamSize, int randomNumberOrigin, int randomNumberBound)`

* `LongStream longs()`

* `LongStream longs(long randomNumberOrigin, long randomNumberBound)`

* `LongStream longs(long streamSize)`

* `LongStream longs(long streamSize, long randomNumberOrigin, long randomNumberBound)`

* `DoubleStream doubles()`

* `DoubleStream doubles(double randomNumberOrigin, double randomNumberBound)`

* `DoubleStream doubles(long streamSize)`

* `DoubleStream doubles(long streamSize, double randomNumberOrigin, double randomNumberBound)`

  产生随机数流。如果提供了 `streamSize`，这个流就是具有给定数量元素的有限流。当提供了边界时，其元素将位于 `randomNumberOrigin` (包含) 和 `randomNumberDound` (不包含) 的区间内
