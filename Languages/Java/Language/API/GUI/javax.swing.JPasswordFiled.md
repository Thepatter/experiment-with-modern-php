*javax.swing.JPasswordFiled*
```java
JPasswordField(String text, int columns);

// 构造一个新的密码域对象

void setEchoChar(char echo);

// 为密码域设置回显字符。独特的观感可以选择自己的回显字符。0 表示重新设置为默认的回显字符

char[] getPassword();

// 返回密码域中的文本。为了安全起见，在使用之后应该覆写返回的数组内容（密码并不是以 String 的形式返回，这是因为字符串在被垃圾回收器回收之前会一直驻留在虚拟机中）

```