## java.util.Currency

* `static Currency getInstance(String currencyCode)`

* `static Currenty getInstance(Locale locale)`

  返回与给定的 `ISO 4127` 货币代号或给定的 `Locale` 中的国家相对应的 `Curreny` 对象。

* `String toString()`

* `String getCurrencyCode()`

  获取该货币的 `ISO 4127` 代码

* `String getSymbol()`

* `String getSymbol(Locale locale)`

  根据默认或给定的 `Locale` 得到该货币的格式化符号。具体是那种形式取决于 `Locale`

* `int getDefaultFractionDigits()`

  获取该货币小数点的默认位数

* `static Set<Currency> getAvailableCurrencies()`

  获取所有可用的货币

  