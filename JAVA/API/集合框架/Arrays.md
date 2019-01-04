## java.util.Arrays

* `static String toString(type[] a)`

  返回包含 a 中元素的字符串，这些数据元素被放在括号内，并用逗号分隔。

  参数：a：类型为 `int`、`long`、`short`、`char`、`byte`、`boolean`、`float`、`double` 的数组

* `static type copyOf(type[] a, int length)` 、`static type copyOfRange(type[] a, int start, int end)`

   `返回与 a 类型相同的一个数组，其长度为 `length` 或者 `end-start` ，数组元素为 a 的值

  ​	a:  类型为 `int`、`long`、`short`、`char`、`byte`、`boolean`、`float`、`double` 的数组

  ​	start：起始下标（包含这个值）

  ​	end：终止下标（不包含这个值）。这个值可能大于 `a.length`。在这种情况下，结果为 0 或 `false`

  ​	length：拷贝的数据元素长度。如果 `length` 值大于 `a.length`，结果为 0 或 `false`；

​        否则，数组中只有前面 `length` 个数据元素的拷贝值

* `static void sort(type[] a)` 

   采用优化的快速排序算法对数组进行排序

​       参数：a：类型为 `int`、`long`、`short`、`char`、`byte`、`boolean`、`float`、`double` 的数组

* `static int binarySearch(type[] a, type v)`、`static int binarySerach(type[] a, int start, int end, type v)`：

  采用二分搜索算法查找值 v。如果查找成功，返回相同的下标值；否则，返回一个负数值 `r`。`-r-1` 是为保持 a 有序 v 应插入的位置

​	a：类型为 `int`、`long`、`short`、`char`、`byte`、`boolean`、`float`、`double` 的 **有序数组**

​	start：起始下标（包含这个值）

​	end：终止下标（不包含这个值）

​	v：同 a 的数据元素类型相同的值

* `static void fill(type[] a, type v)` 

  将数组的所有数据元素值设置为 v

  参数：a：标量数组，v 与 a 数据元素相同的一个值

* `static boolean equals(type[] a，type[] b)` 

  如果两个数组大小相同，并且下标相同的元素都对应相等，返回 `true`

  参数：a、b 为标量数组

* `static <E> List<E> asList(E... array)`

  返回一个数组元素的列表视图。这个数组是可修改的，但其大小不可变