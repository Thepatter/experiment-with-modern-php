## 反射相关 API

### java.lang.Class

* `static Class forName(String className)`

  返回描述类名为 `className` 的 `Class` 对象

* `Object newInstance()`

  返回这个类的一个新实例

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

### java.lang.reflect.Field

### java.lang.reflect.Method

### java.lang.reflect.Constructor

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

* `public Object invoke(Object implicitParameter, Object[] explicitParamenters)` （Method）

  调用这个对象所描述的方法，传递给参数，并返回方法的返回值。对于静态方法，把 `null` 作为隐式参数传递。在使用包装器传递基本类型的值时，基本类型的返回值必须是未包装的

### java.lang.reflect.Modifier

* `static String toString(int modifiers)`

  返回对应 `modifiers` 中位置的修饰符的字符串表示

* `static boolean isAbstract(int modifiers)`

* `static boolean isFinal(int modifiers)`

* `static boolean isInterface(int modifiers)`

* `static boolean isNative(int modifiers)`

* `static boolean isPrivate(int modifiers)`

* `static boolean isProtected(int modifiers)`

* `static boolean isPublic(int modifiers)`

* `static boolean isStatic(int modifiers)`

* `static boolean isStrict(int modifiers)`

* `static boolean isSynchronized(int modifiers)`

* `static boolean isVolatile(int modifiers)`

  这些方法将检测方法名中对应的修饰符在 `modifiers` 值中的位

### java.lang.reflect.AccessibleObject

* `void setAccessible(boolean flag)`

  为反射对象设置可访问标志。`flag` 为 `true` 表明屏蔽 Java 语言的访问检查，使得对象的私有属性也可以被查询和设置

* `boolean isAccessible()`

  返回反射对象的可访问标志的值

* `static void setAccessible(AccessibleObject[] array, boolean flag)`

  是一种设置对象数组可访问标志的快捷方法

### java.lang.reflect.Array

* `static Object get(Object array, int index)`

* `static xxx getXxx(Object array, int index)`

  (xxx 是 boolean，byte，char，double，float，int，long，short 之中的一种基本类型) 

  这些方法将返回存储在给定位置上的给定数组的内容

* `static void set(Object array, int index, Object newValue)`

* `static setXxx(Object array, int index, xxx newValue)`

  (xxx 是 boolean，byte，char，double，float，int，long，short 之中的一种基本类型)

  这些方法将一个新值存储到给定位置上的给定数组中

* `static int getLength(Object array)`

  返回数组的长度

* `static Object newInstance(Class componentType, int length)`

* `static Object newInstance(Class componentType, int[] length)`

  返回一个具有给定类型、给定维数的新数组