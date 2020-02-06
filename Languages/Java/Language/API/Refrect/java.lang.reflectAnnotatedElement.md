*java.lang.reflectAnnotatedElement*

```java
// 如果该项具有给定类型的注解，则返回 true
boolean isAnnotationPresent(Class<? extends Annotation> annotationType);
// 获得给定类型的注解，如果该项不具有这样的注解，则返回 null
<T extends Annotation> T getAnnotation(Class<T> annotationType);
// 获得给定类型的注解，如果该项不具有这样的注解，则返回 null
<T extends Annotation> T getAnnotation(Class<T> annotationType);
// 获得某个可重复注解类型的所有注解，或者返回长度为 0 的数组
<T extends Annotation> T[] getAnnotationsByType(Class<T> annotationType);
// 获得作用于该项的所有注解，包括继承而来的主键。如果没有出现任何注解，那么将返回一个长度为 0 的数组
Annotation[] getAnnotations();
// 获得为该项声明的所有注解，不包含继承而来的注解，如果没有出现任何注解，返回一个长度为 0 的数组
Annotation[] getDeclaredAnnotations();
```

