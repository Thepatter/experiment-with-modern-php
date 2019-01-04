## java.lang.reflect.Method

* `TypeVariable[] getTypeParameters()`

  如果这个方法被声明为泛型方法，则获得泛型类型变量，否则返回长度为 0 的数组

* `Type getGenericReturnType`

  获得这个方法被声明的泛型返回类型

* `Type[] getGenericParameterTypes()`

  获得这个方法被声明的泛型参数类型。如果这个方法没有参数，返回长度为 0 的数组