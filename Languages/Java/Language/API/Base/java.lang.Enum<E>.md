*java.lang.Enum<E>*

```java
// 返回指定名字，给定类的枚举常量
static Enum valueOf(Class enumClass, String name);
// 返回枚举常量名
String toString();
// 返回枚举常量在 enum 声明中的位置，从 0 开始计数
final int ordinal();
// 如果枚举常量出现在 other 之前，则返回一个负值，如果 this == other ，返回 0；否则，返回正值。枚举常量的出现次序在 enum 声明中给出
int compareTo(E other);
```