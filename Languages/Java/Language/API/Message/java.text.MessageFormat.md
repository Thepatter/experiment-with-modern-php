*java.text.MessageFormat*
```java
MessageFormat(String pattern);

MessageFormat(String pattern, Locale loc);

// 用给定的模式和 `locale` 构建一个消息格式对象

void applyPattern(String pattern);

// 给消息格式对象设置特定的模式

void setLocal(Locale loc);

Locale getLocale();

// 设置或获取消息中占位符所使用的 `locale` 。这个 `locale` 仅仅被通过调用 `applyPattern` 方法所设置的后续模式所使用

static String format(String pattern, Object... args);

// 通过使用 `args[i]` 作为占位符 `{i}` 的输入来格式化 `pattern` 字符串

StringBuffer format(Object args, StringBuffer result, FieldPosition pos);

// 格式化 `MessageFormat` 的模式。`args` 参数必须是一个对象数组。被格式化的字符串会被附加到 `result` 末尾，并返回 `result`。如果 `pos` 等于 `new FieldPosition(MessageFormat.Field.ARGUMENT)`，就用它的 `beginIndex` 和 `endIndex` 属性值来设置替换占位符 `{1}` 的文本位置。如果不关心位置信息，可以将它设为 `null`

```