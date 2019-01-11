## java.util.Optional

* `static <T> Optional<T> of(T value)`

* `static <T> Optional<T> ofNullable(T value)`

    产生一个具有给定值的 `Optional`, 如果 `value` 为 `null`, 那么第一个方法抛出一个 `NullPointerException` 对象，第二个方法产生一个空 `Optional`

* `static <T> Optional<T> empty()`

    产生一个空 `Optional`

* `T orElse(T other)`

    产生这个 `Optional` 的值，或者在该 `Optional` 为空时，产生 `other`

* `T orElseGet(Supplier<? extends T> other)`

    产生这个 `Optional` 的值，或者在该 `Optional` 为空时，产生调用 `other` 的结果

* `<X extends Throwable> T orElseThrow(Supplier<? extends X> exceptionSupplier)`

    产生这个 `Optional` 的值，或者在该 `Optional` 为空时，抛出调用 `exceptionSupplier` 的结果

* `void ifPresent(Consumer<? super T> consumer)`

    如果该 `optional` 不为空，那么就将它的值传递给 `consumer`

* `<U> Optional<U> map(Function<? super T,？ extends U> mapper)`

    产生将该 `Optional` 的值传递给 `mapper` 后的结果，只要这个 `Optional` 不为空且结果不为 null，否则产生一个空 `Optional`

* `T get()`

    产生这个 `Optional` 的值，或者在该 `Optional` 为空时，抛出一个 `NoSuchElementException` 对象

* `boolean isPresent()`

    如果该 `Optional` 不为空，则返回 `true`

* `<U> Optional<U> flatMap(Function<? super T, Option<U>> mapper)`

    产生将 `mapper` 应用于当前的 `Optional` 值所产生的结果，或者在当前 `Optional` 为空时，返回一个空 `Optional`

* `static Optional(Int|Long|Double) of((int|long|double) value)`

    用所提供的基本类型产生一个可选对象

* `(int|long|double) getAs(Int|Long|Double)()`

    产生当前可选对象的值，或者在其为空时抛出一个 `NoSuchElementException` 异常

* `(int|long|double) orElse((int|long|double) other)`

* `(int|long|double) orElseGet((Int|Long|Double)Supplier other)`

    产生当前可选对象的值，或者在这个对象为空时产生可替代的值

* `void ifPresent((Int|Long|Double)Consumer consumer)`

    如果当前可选对象不为空，则将其值传递给 `consumer`

    