## java.security.ProtectionDomain

* `ProtectionDomain(CodeSource source, PermissionCollection permissions)`

  用给定的代码来源和权限构建一个保护域

* `CodeSource getCodeSource()`

  获取该保护域的代码来源

* `boolean implies(Permission p)`

  如果该保护域允许给定的权限，则返回 true