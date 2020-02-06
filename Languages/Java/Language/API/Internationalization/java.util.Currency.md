*java.util.Currency*

```java
// 返回给定 ISO4127 货币代号相对应的 Currency 对象
static Currency getInstance(String currencyCode);
// 返回给定 Locale 中国家相对应的 Currency 对象
static Currency getInstance(Locale locale);
// 获取该货币 ISO 4127 代码
String getCurrencyCode();
// 获取该货币的格式化符号
String getSymbol();
// 获取给定 Locale 货币的格式化符号
String getSymbol(Locale locale);
// 获取该货币小数点的默认位数
int getDefaultFractionDigits();
// 获取所有可用的货币
static Set<Currency> getAvailableCurrencies();
```

