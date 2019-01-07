## java.lang.reflect.AccessibleObject

* `void setAccessible(boolean flag)`

   为反射对象设置可访问标志。`flag` 为 `true` 表明屏蔽 Java 语言的访问检查，使得对象的私有属性也可以被查询和设置

* `boolean isAccessible()`

   返回反射对象的可访问标志的值

* `static void setAccessible(AccessibleObject[] array, boolean flag)`

   是一种设置对象数组可访问标志的快捷方法