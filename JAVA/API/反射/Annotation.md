### java.lang.annotation.Annotation

* `Class<? extends Annotation> annotationType()`

  返回 `Class` 对象。它用于描述该注解对象的注解接口。调用注解对象上的 `getClass` 方法可以返回真正的类，而不是接口

* `boolean equals(Object other)`

  如果 `other` 是一个实现了与该注解对象相同的注解接口的对象，并且如果该对象和 `other` 的所有元素彼此相等。那么返回 `true`

* `int hashCode()`

  返回一个与 `equals` 方法兼容、由注解接口名以及元素值衍生而来的散列码

* `String toString()`

  返回一个包含注解接口名以及元素值的字符串表示