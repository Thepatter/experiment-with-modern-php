### `java.util.Comparator<T>`

* `static <T extends Comparable<? super T>> Comparator<T> reverseOrder()`

  生成一个比较器，将逆置 `Comparable` 接口提供的顺序

* `default Comparator<T> reversed()`

  生成一个比较器，将逆置这个比较器提供的顺序
