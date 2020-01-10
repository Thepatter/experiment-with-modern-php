*java.lang.reflect.TypeVariable*

```java
// 获得类型变量的名字
String getName();
// 获得类型变量的子类限定，否则，如果该变量无限度，则返回长度为 0 的数组
Type[] getBounds();
```