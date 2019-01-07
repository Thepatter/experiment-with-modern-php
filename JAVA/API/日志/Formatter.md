## java.util.logging.Formatter

* `abstract String format(LogRecord record)`

  返回对日志记录格式化后得到的字符串

* `String getHead(Handler h)`

* `String getTail(Handler h)`

  返回应该出现在包含日志记录的文档的开头和结尾的字符串。超类 `Formatter` 定义了这些方法，它们只返回空字符串。如果必要的话，可以对它们进行覆盖

* `String formatMessage(LogRecord record)`

  返回经过本地化和格式化后的日志记录的消息内容
