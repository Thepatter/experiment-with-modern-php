## java.security.Permission

* `Permission(String name)`

  用指定的目标名称构建一个权限

* `String getName()`

  返回该权限的对象名称

* `boolean implies(Permission other)`

  检查该权限是否隐含了 other 权限。如果 other 权限描述了一个更加具体的条件，而这个具体条件是由该权限所描述的条件所产生的结果，那么该权限就隐含这个 other 权限

