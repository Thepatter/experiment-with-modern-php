## java.lang.Class

* `static Class forName(String className)`

  返回描述类名为 `className` 的 `Class` 对象

* `Object newInstance()`

  返回这个类的一个新实例

* `T newInstance()`

  返回无参数构造器构造的一个新实例

* `T cast(Object obj)`

  如果 obj 为 null 或有可能转换成类型 T，则返回 obj；否则抛出 `BadCastException` 异常

* `T[]  getEnumConstants()`

  如果 T 是枚举类型，则返回所有值组成的数组，否则返回 null

* `Class<? super T> getSuperclass()`

  返回这个类的超类。如果 T 不是一个类或 Object 类，则返回 null

* `Constructor<T> getConstructor(Class... parameterTypes)`

* `Constructor<T> getDeclaredConstructor(Class...parameterTypes)`

  获得公有的构造器，或带有给定参数类型的构造器

* `Field getField(String name)`

* `Field[] getField()`

  返回指定名称的公有域，或包含所有域的数组

* `Field getDeclaredField(String name)`

  `Field[] getDeclaredFields()`

  返回类中声明的给定名称的域，或者包含声明的全部域的数组

* `Field[] getFields()`

* `Field[]  getDeclaredFields()`

  `getFields` 方法将返回一个包含 `Field` 对象的数组，这些对象记录了这个类或其超类的公有域。

  `getDeclareFields()` 方法也将返回包含 `Field` 对象的数组，这些对象记录这个类的全部域。如果类中没有域，或者 `Class` 对象描述的是基本类型或数组类型，这些方法将返回一个长度为 0 的数组

* `Method[] getMethods()`  

* `Method[] getDeclareMethods()`

  返回包含 `Method` 对象的数组：`getMethods()` 将返回所有的公有方法，包括从超类继承来的公有方法；`getDeclaredMethods` 返回这个类或接口的全部方法，但不包括由超类继承了的方法

* `Constructor[] getConstructors()`

* `Constructor[] getDeclaredConstructors()`

  返回包含 `Constructor` 对象的数组，其中包含了 `Class` 对象所描述的类的所有公有构造器（`getConstructors()`）或者所有构造器(`getDeclaredConstructors()`)

* `TypeVariable[] getTypeParameters()`

  如果这个类型被声明为泛型类型，则获得泛型类型变量，否则获得一个长度为0 的数组

* `Type getGenericSuperclass()`

  获得被声明为这一类型的超类的泛型类型；如果这个类型是 Object 或不是一个类类型，则返回 null

* `Type[] getGenericInterfaces()`

  获得被声明为这个类型的接口的泛型类型（以声明的次序），否则，如果这个类型没有实现接口，返回长度为0 的数组