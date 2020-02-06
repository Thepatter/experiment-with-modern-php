java.text.Collator

```java
// 返回 Locale 数组，该 Collator 对象可用于这些对象
static Locale[] getAvailableLocales();
// 为默认或给定 Locale 返回一个排序器
static Collator getInstance();
static Collator getInstance(Locale l);
// 如果 a 在 b 之前，则返回负值；如果等价，返回 0，否则返回正值
int compare(String a, String b);
// 如果它们相等，则返回 `true`, 否则返回 `false`
boolean equals(String a, String b);
// 获取排序器的强度，更强的排序器可以区分更多的词
int getStrength();
// 设置排序器的强度，strenth 值为：Collator.PRIMARY Collator.SECONDARY Collator.TERTIARY
void setStrength(int strength);
// 获取排序器的分解模式，分解越细，判断两个字符串是否相等时就越严格
int getDecompostition();
// 设置排序器分解模式，decomp 值为：Collator.NO_DECOMPOSITION Collator.CANONICAL_DECOMPOSITION COllator.FULL_DECOMPOSITION
void setDecomposition(int decomp);
// 返回一个排序器键，这个键包含一个对一组字符按特定格式分解的结果，可以快速和其他排序器键比较
CollationKey getCollationKey(String a);
```