## java.lang.reflect.Array

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