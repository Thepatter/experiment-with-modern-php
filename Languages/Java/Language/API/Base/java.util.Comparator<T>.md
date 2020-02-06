java.util.Comparator<T>

```java
// 生成一个比较器
static <T extends Comparable<? super T>> Comparator<T> reverseOrder();
// 生成一个比较器，将逆置这个比较器提供的顺序
default Comparator<T> reversed();
```