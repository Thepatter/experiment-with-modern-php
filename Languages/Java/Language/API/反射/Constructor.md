*java.lang.reflect.Constructor*

```java
// 返回一个用于描述类中定义的构造器，方法或域的 Class 对象
Class getDeclaringClass();
// 返回一个用于描述方法抛出异常类型的 Class 对象数组
Class[] getExceptionTypes();
// 返回一个用于描述构造器，方法或域的修饰符的整形数值
int getModifiers();
// 返回一个用于描述构造器、方法或域名的字符串
String getName();
// 返回一个用于描述参数类型的 Class 对象数组
Class[] getParameterTypes();
// 返回一个用于描述返回类型的 Class 对象，在 Method 类中
Class getReturnType();
// 构造一个这个构造器所属类的新实例，在 Constructor 类中
Object newInstance(Object[] args);
// 返回用指定参数构造的新实例,在 Constructor 类中
T newInstance(Object...parameters);
// 调用这个对象所描述的方法，传递给参数，并返回方法的返回值。对于静态方法，把 null 作为隐式参数传递，使用包装器传递基本类型的值时，基本类型的返回值必须时未包装的, 在 Method
Object invoke(Object implicitParameter, Object[] explicitParamenters);
```