## java.lang.reflect.Constructor

* `Class getDeclaringClass()`

  返回一个用于描述类中定义的构造器、方法或域的 `Class` 对象

* `Class[] getExceptionTypes()`  （在 `Constructor` 和 `Method` 类中）

  返回一个用于描述方法抛出的异常类型的 `Class` 对象数组

* `int getModifiers()`

  返回一个用于描述构造器、方法或域的修饰符的整型数值。使用 `Modifier` 类中的这个方法可以分析这个返回值

* `String getName()`

  返回一个用于描述构造器、方法或域名的字符串

* `Class[] getParameterTypes()`  （在 `Constructor` 和 `Method` 类中）

  返回一个用于描述参数类型的 `Class` 对象数组

* `Class getReturnType()` （在 `Method` 类中）

  返回一个用于描述返回类型的 `Class` 对象

* `Object newInstance(Object[] args)`  （在 `Constructor` 类中）

  构造一个这个构造器所属类的新实例

  参数：`args` 提供给构造器的参数

* `T newInstance(Object...parameters)` （在 `Constructor` 类中）

  返回用指定参数构造的新实例

* `public Object invoke(Object implicitParameter, Object[] explicitParamenters)` （Method）

  调用这个对象所描述的方法，传递给参数，并返回方法的返回值。对于静态方法，把 `null` 作为隐式参数传递。在使用包装器传递基本类型的值时，基本类型的返回值必须是未包装的