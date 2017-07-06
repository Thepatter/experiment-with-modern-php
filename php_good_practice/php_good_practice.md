##PHP开发良好实践
###过滤输入
转义或删除不谙屈戴的字符，在数据到达应用戴的存储层之前过滤。
####html过滤
使用`htmlentities()`函数过滤html，把特殊字符转换成对应的html实体，`htmlentities()`函数用法
```$xslt
htmlentities($input, ENT_QUOTES, 'UTF-8');
```
第一个参数是输入字符串，第二个参数设为ENT_QUOTES常量，转义单引号，第三个参数设为输入字符串的字符集
####SQL查询
在SQL查询中一定不能使用未过滤的输入数据。使用PDO预处理语句。
####过滤用户资料使用
```$xslt
mixed filter_var(mixed $variable [,int $filter = FILTER_DEFAULT [, mixed $options]])
```
删除小鱼ASCII32戴的字符，转义大于ASCII127的字符
```$xslt
$safestring = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_ENCODE_HIGH)
```
####验证数据
把某个FILTER_VALIDATE_*标志传给filter_var()函数，成功返回要验证戴的值，失败返回false
###转义输出
可以使用htmlentities()函数转义输出，第二个参数使用ENT_QUOTES，转义单双引号，第三个参数指定字符集
###密码
####不能知道用户的密码
####不要越是用户的密码
####不要使用email发送密码
应该在电子邮件中发送用于设定或修改密码的URL，web应用通常会生成一个唯一的令牌，这个令牌只在设定或修改密码时
使用一次，例如，我忘记自己在你应用中戴的账户密码，单机忘记密码的链接，转到一个表单，我在这个表单中填写我的电
子邮件地址，请求重设密码，应用生成一个唯一的令牌，并把这个令牌关联得到我的电子邮件地址对应的账户上，然后发送
一封电子邮件到账户的电子邮件地址，这封电子邮件中有一个URL，其中某个URL片段或查询字符串的值是这个唯一的令牌
我访问这个URL，你的应用验证令牌，令牌有限，则重设密码， 重设密码后，令牌设置为失效。