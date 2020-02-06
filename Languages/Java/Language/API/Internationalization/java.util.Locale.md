*java.util.Locale*

```java
// 使用指定语言代码实例化 Locale
Locale(String language);
// 使用指定语言，国家实例化 Locale
Locale(String language, String country);
// 使用语言、国家、变体实例化 Locale
Locale(String language, String country, String variant);
// 生成给定语言标签 Locale 对象
static Locale forLanguageTag(String languageTag);
// 返回默认 Locale
static Locale getDefault();
// 返回当前 Locale 名字
String getDisplayName();
// 返回给定 Locale 名字
String getDisplayName(Locale locale);
// 返回给定 Locale 中语言名称
String getDisplayLanguage(Locale locale);
// 返回国家代码，两个大写字母组成的 ISO-3166 代码
String getCountry();
// 返回国家名
String getDisplayCountry();
// 返回语言标签
String toLanguageTag();
// 返回 Locale 描述，包括语言和国际，使用下划线分隔
String toString();
```

