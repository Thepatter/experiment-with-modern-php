*java.lang.Class<T>*

```java
// 返回类的名字
String getName();
// 返回超类的 Class 对象
Class getSupperclass();
// 返回描述类名为 className 的 class 对象
static Class forName(String className);
// 返回类的新实例
Object newInstance();
// 返回无参数构造器构造的一个新实例
T newInstance();
// 如果 obj 为 null 或由可能转换成类型 T，则返回 obj；否则抛出 BadCastException
T cast(Object obj);
// 如果 T 是枚举类型，则返回所有值组成的数组，否则返回 null
T[] getEnumConstants();
// 返回这个类的超类，如果 T 不是一个类或 Object 类，则返回 null
Class<? super T> getSuperclass();
// 获得公有的构造器，或带有给定参数类型的构造器
Constructor<T> getConstructor(class... parameterTypes);
Constructor<T> getDeclaredConstructor(class... parameterTypes);
// 返回指定名称的公有域
Field getField(String name);
// 返回包含所有域的数组
Field[] getField();
// 返回类中声明的给定名称的域
Field getDeclaredField(String name);
// 返回包含声明的全部域的数组
Field[] getDeclaredFields();
// 返回一个包含 Field 对象的数组，这些对象记录了这个类或其超类的公有域
Field[] getFields();
// 返回包含 Field 对象的数组，这些对象记录这个类的全部域。如果类中没有域，或 class 对象描述的是基本类型或数组类型，这些方法将返回一个长度为 0 的数组
Field[] getDeclaredFields();
// 返回所有的公有方法，包括从超类继承来的公有方法
Method[] getMethods();
// 返回这个类或接口的全部方法，但不包括由超类继承了的方法
Method[] getDeclareMethods();
// 返回 constructor 对象的数组，其中包含了 Class 对象所描述的类的所有公有构造器
Constructor[] getConstructors();
// 返回 constructor 对象的数组，其中包含拿了 class 对象所描述的类的所有构造器
Constructor[] getDeclaredConstructors();
// 如果这个类型被声明为泛型类型，则获得泛型类型变量，否则获得一个长度为 0 的数组
TypeVariable[] getTypeParameters();
// 获取被声明为这一类型的超类的泛型类型，如果这个类型是 object 或不是一个类类型，则返回 null
Type getGenericSuperclass();
// 获得被声明为这个类型的接口的泛型类型（以声明次序），否则，如果这个类型没有实现接口，返回长度为 0 的数组
Type[] getGenericInterfaces();
// 获取加载该类的类加载器
ClassLoader getClassLoader();
// 获取该类的保护域，如果该类被加载时没有保护域，则返回 null
ProtectionDomain getProtectionDomain();
```