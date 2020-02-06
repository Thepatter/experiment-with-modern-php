*java.lang.reflect.WildcardType*

```java
// 获得这个类型变量的子类限定，否则，如果没有子类限定，则返回长度为 0 的数组
Type[] getUpperBounds();
// 获得这个类型变量的超类限定，如果没有超类限定，则返回长度为 0 的数组
Type[] getLowerBounds();
```