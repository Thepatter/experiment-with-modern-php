## java.lang.ClassLoader

* `void setDefaultAssertionStatus(boolean b)`

    对于通过类加载器加载的所有类来说，如果没有显式地说明类或包的断言状态，就启用或禁用断言

* `void setClassAssertionStatus(String className, boolean b)`

    对于给定的类和它的内部类，启用或禁用断言

* `void setPackageAssertionStatus(String packageName, boolean b)`

    对于给定包和其子包中的所有类，启用或禁用断言

* `void clearAssertionStatus()`

    移出所有类和包的显示断言状态设置，并禁用所有通过这个类加载器加载的类的断言

* `static <T extends Comparable<? super T>> Comparator<T> reverseOrder()`

    生成一个比较器，将逆置 `Comparable` 接口提供的顺序

* `default Comparator<T> reversed()`

    生成一个比较器，将逆置这个比较器提供的顺序

* `ClassLoader getParent()`

    返回父类加载器，如果父类加载器是引导类加载器，则返回 null

* `static ClassLoader getSystemClassLoader()`

    获取系统类加载器，即用于加载第一个应用类的类加载器

* `protected Class findClass(String name)`

    类加载器应该覆盖该方法，以查找类的字节码，并通过调用 `defineClass` 方法将字节码传给虚拟机。在类的名字中，使用 `.` 作为包名分隔符，并且不使用 `.class` 后缀

* `Class defineClass(String name, byte[] byteCodeData, int offset, int length)`

    将一个新的类添加到虚拟机中，其字节码在给定的数据范围中

    