*java.lang.annotation.Annotation*

```java
// 返回 Class 对象。用于描述该注解对象的注解接口，调用注解对象上的 getClass 方法可以返回真正的类，而不是接口
Class<? extends Annotation> annotationType()
// 如果 other 是一个实现了与该注解对象相同的注解接口的对象，并且该对象和 other 所有元素彼此相等，返回 true
boolean equals(Object other);
// 返回一个与 equals 方法兼容、由注解接口名及元素值衍生的散列码
int hashCode();
// 返回一个包含注解接口名及元素值的字符串表示
String toString();
```