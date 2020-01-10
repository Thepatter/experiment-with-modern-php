*java.lang.reflect.Modifier*

```java
// 返回对应 modifiers 中位置的修饰符的字符串表示
static String toString(int modifiers);
static boolean isAbstract(int modifiers);
static boolean isFinal(int modifiers);
static boolean isInterface(int modifiers);
static boolean isNative(int modifiers);
static boolean isPrivate(int modifiers);
static boolean isProtected(int modifiers);
static boolean isPublic(int modifiers);
static boolean isStatic(int modifiers);
static boolean isStrict(int modifiers);
static boolean isSynchronized(int modifiers);
// 这些方法将检测方法名中对应的修饰符在 modifiers 值中的位
static boolean isVolatile(int modifiers);
```

