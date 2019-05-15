## java.lang.reflect.ParameterizedType

* `Type getRawType()`

  获得这个参数化类型的原始类型

* `Type[] getActualTypeArguments()`

  获得这个参数化类型声明时所使用的类型参数

* `Type getOwnerType()`

  如果是内部类型，则返回其外部类型，如果是一个顶级类型，则返回 null