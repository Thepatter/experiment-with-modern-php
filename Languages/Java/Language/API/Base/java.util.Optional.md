*java.util.Optional*

// 一个容器对象

```java
// 产生一个具有给定值的 Optional，如果 value 为 null，抛出 NullPointerException
static <T> Optional<T> of(T value);
// 产生一个具有给定值的 Optional，如果 value 为 null, 产生一个空 Optional
static <T> Optional<T> ofNullable(T value);
// 产生一个空 Optional
static <T> Optional<T> empty();
// 产生这个 Optional 的值，或者在该 Optional 为空时，产生 other
T orElse(T other);
// 产生这个 Optional 的值，或者在该 Optional 为空时，产生调用 other 的调用
T otElseGet(Supplier<? extends T> other);
// 产生这个 Optional 的值，或者在该 Optional 为空时，抛出调用 e xceptionSupplier 结果
<X extends Throwable> T orElseThrow(Supplier<? extends X>exceptionSupplier);
// 如果该 optional 不为空，那么就将它的值传递给 consumer
void ifPresent(Consumer<? super T> consumer);
// 将该 Optional 值传递给 mapper 后的结果，只要这个 Optional 不为空且结果不为 null，否则产生空 Optional
<U> Optional <U> map(Function<? super T, ? extends U> mapper);
// 产生这个 Optional 的值，或者在该 Optional 为空时，抛出一个 NoSuchElementException 对象
T get();
// 如果该 Optional 不为空，返回 true
boolean isPresent();
// 产生将 mapper 应用于当前的 Optional 值所产生的结果，活在在当前 Optional 为空时，返回一个空 Optional
<U> Optional<U> flatMap(Function<? super T, Option<U>> mapper);
// 用所提供的基本类型产生一个可选对象
static <T> Optional<T> of(T value)
// 当前没有值
boolean isEmpty();
// 如果当前可选对象不为空，则将其值传递给 consumer
void ifPresent(Consumer<? super T> action)
```