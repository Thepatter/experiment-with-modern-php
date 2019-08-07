*java.lang.reflect.Field*

```java
// 返回一个用于描述符类中定义的构造器、方法或域的 Class 对象
Class getDeclaringClass()
// 返回一个用于描述构造器、方法或域的修饰符的整型数值。使用 Modifier 类中的这个方法可以分析这个返回值
int getModifiers()
// 返回一个用于描述构造器、方法或域名的字符串
String getName()
// 返回 obj 对象中用 Field 对象表示的域值
Object get(Object obj)
// 用一个新值设置 Obj 对象中的 Field 对象表示的域
void set(object obj, Object newValue)
```

