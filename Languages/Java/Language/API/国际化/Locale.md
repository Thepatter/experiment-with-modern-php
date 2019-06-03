## java.util.Locale

* `Locale(String language)`

* `Locale(String language, String country)`

* `Locale(String language, String country, String variant)`

  用给定的语言、国家和变量创建一个 `Locale` 。在新代码中不要使用变体，应该使用 `IETF BCP 47` 语言标签

* `static Locale forLanguageTag(String languageTag)`

  构建与给定的语言标签相对应的 `Locale` 对象

* `static Locale getDefault()`

  返回默认的 `Locale`

* `static void setDefault(Locale loc)`

  设定默认的 `Locale`

* `String getDisplayName()`

  返回一个在当前的 `Locale` 中所表示的用来描述 `Locale` 的名字

* `String getDisplayName(Locale loc)`

  返回一个在给定的 `Locale` 中所表示的用来描述 `Locale` 的名字

* `String getLanguage()`

  返回语言代码，它是两个小写字母组成的 `ISO-639` 代码

* `String getDisplayLanguage()`

  返回在当前 `Locale` 中所表示的语言名称

* `String getDisplayLanguage(Locale loc)`

  返回在给定 `Locale` 中的所表示的语言名称

* `String getCountry()`

  返回国家代码，由两个大写字母组成的 `ISO-3166` 代码

* `String getDisplayCountry()`

  返回在当前 `Locale` 中所表示的国家名

* `String getDisplayCountry(Locale loc)`

  返回在当前 `Locale` 中所表示的国家名

* `String toLanguageTag()`

  返回该 `Locale` 对象的语言标签

* `String toString()`

  返回 `Locale` 的描述，包括语言和国家，用下划线分隔。用过只在调试时使用该方法

  