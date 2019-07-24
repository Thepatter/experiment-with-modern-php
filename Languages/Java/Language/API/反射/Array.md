*java.lang.reflect.Array*

```java
// 返回存储在给定位置上的给定数组的内容
static Object get(Object array, int index);
// 返回 存储在给定位置上的 boolean, byte, char, double, float, int, long, short 类型
static xxx getXxx(Object array, int index);
// 将新值存储到给定位置上的给定数组中
static void set(Object array, int index, Object newValue);
// 将 boolean, byte, char, double, float, int, long, short 类型存储到给定数组的给定位置
static setXxx(Object array, int index, xxx newValue);
// 返回数组的长度
static int getLength(Object array);
// 返回具有给定类型，给定维数的新数组
static Object newInstance(Class componentType, int length);
static Object newInstance(Class componentType, int[] length);
```