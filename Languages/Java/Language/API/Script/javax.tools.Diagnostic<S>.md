*javax.tools.Diagnostic<S>*
```java
S getSource();

// 获取与该诊断信息相关联的源对象

Diagnostic.Kind getKind();

// 获取该诊断信息的类型，返回值为 `ERROR`，`WARNING`，`MANDATORY_WARNING`，`NOTE`，`OTHER` 之一

String getMessage(Locale locale);

// 获取一条消息，这条消息描述了由该诊断信息所揭示的问题。如果要使用默认的 `Locale`，则传递 `null`

long getLineNumber();

long getColumnNumber();

// 获取由该诊断信息所揭示的问题的位置

```