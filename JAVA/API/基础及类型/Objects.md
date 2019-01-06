## java.util.Arrays

* `static boolean equals(Object a, Object b)`

    如果 a 和 b 都为 null，返回 true, 如果只有其中之一为 null，则返回 false，否则返回 `a.equals(b)`
    
* `static int hash(Object... objects)`

    返回一个散列码，由提供的所有对象的散列码组合而得到
    
* `static int hashCode(Object a)`

    如果 a 为 null 返回 0，否则返回 `a.hashCode()`