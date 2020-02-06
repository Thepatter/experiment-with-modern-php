*java.lang.reflect.ParameterizedType*

```java
// 获得这个参数化类型的原始类型
Type getRawType();
// 获得这个参数化类型声明时所使用的类型参数
Type[] getActualTypeArguments();
// 如果是内部类型，则返回其外部类型，如果是一个顶级类型，则返回 null
Type getOwnerType();
```