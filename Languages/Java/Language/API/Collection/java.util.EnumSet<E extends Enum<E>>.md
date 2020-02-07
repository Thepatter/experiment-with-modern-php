*java.util.EnumSet<E extends Enum<E>>*
```java
static <E extends Enum<E>> EnumSet<E> allOf(Class<E> enumType);

// 返回一个包含给定枚举类型的所有值的集

static <E extends Enum<E>> EnumSet<E> noneOf(Class<E> enumType);

// 返回一个空集，并有足够的空间保存给定的枚举类型所有的值

static <E extends Enum<E>> EnumSet<E> range(E from, E to);

// 返回一个包含 from ~ to 之间的所有值（包括两个边界元素）的集

static <E extends Enum<E>> EnumSet<E> of(E value);

static <E extends Enum<E>> EnumSet<E> of(E value, E... values);

// 返回包括给定值的集

```