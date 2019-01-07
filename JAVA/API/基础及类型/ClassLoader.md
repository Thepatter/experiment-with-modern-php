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