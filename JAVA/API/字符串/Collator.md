## java.text.Collator

* `static Locale[]  getAvailableLocales()`

  返回 `Locale` 对象的一个数组，该 `Collator` 对象可用于这些对象

* `static Collator getInstance()`

* `static Collator getInstance(Locale l)`

  为默认或给定的 `Locale` 返回一个排序器

* `int compare(String a, String b)`

  如果 a 在 b 之前，则返回负值；如果它们等价，则返回 0，否则返回正值

* `boolean equals(String a, String b)`

  如果它们相等，则返回 `true`, 否则返回 `false`

* `void setStrength(int strength)`

* `int getStrength()`

  设置或获取排序器的强度。更强的排序器可以区分更多的词。强度的值可以是 `Collator.PRIMARY`、`Collator.SECONDARY` 、`Collator.TERTIARY`

* `void setDecomposition(int decomp)`

* `int getDecomposition()`

  设置或获取排序器的分解模式。分解越细，判断两个字符串是否相等时就越严格。分解的等级值可以是 `Collator.NO_DECOMPOSITION`，`Collator.CANONICAL_DECOMPOSITION` 、`Collator.FULL_DECOMPOSITION`

* `CollationKey getCollationKey(String a)`

  返回一个排序器键，这个键包含一个对一组字符按特定格式分解的结果，可以快速地和其他排序器键进行比较