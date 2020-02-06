*java.security.ProtectionDomain*

```java
// 用给定的代码来源和权限构建一个保护域
ProtectionDomain(CodeSource source, PermissionCollection permissions);
// 获取该保护域的代码源
CodeSource getCodeSource();
// 如果该保护域允许给定的权限，则返回 true
boolean implies(Permission p);
```