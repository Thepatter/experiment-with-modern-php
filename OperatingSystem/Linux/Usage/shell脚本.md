### shell 脚本

#### 语法

##### 注释

只要 Shell 碰到了特殊字符 `#`，就会忽略 `#` 之后，直到行尾的所有字符。如果 `#` 出现在行首，那么这一整行都被视为注释。

##### 变量

变量名以字母或`_` 开头，后面可以跟上零个或多个字母及数字字符或下划线，变量在使用前不需要声明，在用时直接赋值就行，变量赋值等号两边不能有空格，`shell` 没有数据类型，只有字符串。

* `{}` 操作符

  避免歧义，只有在变量名的最后一个字符后面跟的是字母及数字字符或下划线的时候才有必要使用花括号

* 整数运算操作

  `$((expression))`

##### 引用

shell 能够识别 4 种不同的引用字符：

* 单引号

  忽略所有特殊字符，给变量赋值，其中包含空白字符或其他特殊字符

* 双引号

  作用类似单引号，忽略大部分特殊字符（`$`，反引号，反斜线）

* 反斜线

  转义字符，作为一行最后一个字符续行

* 反引号

  将其中的命令使用命令输出代替

##### 命令替换

shell 能够在命令行中的任何位置使用命令的输出来替换特定的命令：将命令放在反引号中或放在`$(...)` 中，替换命令支持多个命令，用分号分隔，还可以使用管道

```shell
echo current path: `pwd`
# 等效
echo current path: $(pwd;date)
# 单引号中命令会原样输出
echo '$(who | wc -l) tells how many users ar logged in'
# 双引号中会替换执行
echo "$(ls | wc -l) files in your directory"
```

shell 是在替换过命令输出后才执行文件名替换。将命令放在双引号中能够阻止 shell 针对命令的输出再做文件名替换。

##### 内置算术操作符

`expr` 能够识别常用的算术操作符 `+`，`-`，`/`，`*`，`%`，操作数与操作符直接必须用空格分隔。

```shell
# 转义 * 不然会报错
echo $(expr 12 \* 2)
```

#### 传参

* 位置参数

  执行 Shell 程序，Shell 会自动将第一个参数保存在特殊的 Shell 变量 1 中，将第二个参数保存在 Shell 变量 2 中，即位置参数（基于参数在命令行中所处的位置）是在 Shell 完成正常的命令行处理之后（I/O 重定向，变量替换，文件名替换）被赋值的。当需要访问第 10 个参数时 `${10}`

* 参数个数

  `$#` 变量包含了命令行中输入的参数个数

* 所有参数

  `$*` 引用的是传给程序的所有参数

* 移动参数

  `shift` 命令会向左移动位置参数，`$2` 中的内容会分配给 `$1`，`$1` 中的旧值会丢失，`$#` 值会减 1。如果在没有位置参数可移动的情况下（`$#` 已经为 0）的时候使用 `shift`，Shell 会发出错误信息。可以在 `shift` 后加数字，指定一次移动多个位置 `shift 3`

#### 条件语句

##### 退出状态

只要程序执行完成，就会向 shell 返回一个退出状态码。这个状态码是一个数值，指明了程序运行是否成功。0 表示程序运行成功；非 0 的退出状态码表示程序运行失败，不同的值对应着不同的失败原因；对于管道，退出状态对应的是管道中的最后一个命令

* `if` 语句

  ```shell
  if command1
  then
  	command
  	command
  fi
  ```

  `command1` 是要执行的命令，命令的退出状态会被测试。如果退出状态为 0，执行 `then` 和 `fi` 之间的命令，否则，跳过这些命令；

  if 命令中可以加入 else，commandt 会被执行并对其退出状态求值。如果为 0，执行 then 代码块，并忽略 else 代码块。如果为非 0，则忽略 then 代码块，执行 else 代码块

  ```shell
  if commandt
  then
  	command
  	command
  else
  	command
  	command
  fi
  # 简洁模式
  if condition then statements-if-true else statement-if-false fi
  ```

  shell 支持特殊的 elfi，其行为类似于 `else if <em> condition`，但它不会增加嵌套层级

  ```shell
  hour=$(date | cut -c12-13)
  if ["$hour" -ge 0 -a "$hour" -le 11 ]
  then
  	echo "Good morning"
  elif [ "$hour" -ge 12 -a "$hour" -le 17 ]
  then
  	echo "Good afternoon"
  else
  	echo "Good evening"
  ```

* case

  case 命令可以将单个值与一组值或表达式进行比较，在有匹配的时候执行一个或多个命令

  ```shell
  case value in
  pattern1)
  	command;;
  pattern2)
  	command;;
  pattern3)
  	command;;
  esac
  ```

  `case` 可以使用正则表达式

* shell 会将变量 `$?` 自动设置为最后一条命令的退出状态。

* test 命令测试单个或多个条件

  `test expression`，`expression` 描述了待测试的条件。test 会对 `expression` 求值，如果结果为真，返回为 0 的退出状态码；如果结果为假，返回非 0 的退出状态码。操作符 `=` 用来测试两个值是否一样。test 命令所有的操作数和操作符都必须是独立的参数，彼此之间必须使用一个或多个空白字符分隔。

  `if` 语句会测试 test 返回的退出状态。如果为 0，执行 `then` 和 `fi` 之间的命令，否则跳过

  *test字符串操作符*

  |       操作符       |        为真条件        |
  | :----------------: | :--------------------: |
  | string1 = string2  |  string1 等于 string2  |
  | string1 != string2 | string1 不等于 string2 |
  |       string       |     string 不为空      |
  |     -n string      |     string 不为空      |
  |     -z string      |      string 为空       |

  `test` 命令可以使用另外一种格式来表示：

  ```shell
  [ expression ]
  if [ "$name" = julio ]
  then
  	echo "Would you like to play a game?"
  fi
  ```

  *test整数操作符*

  |    操作符     |       为真条件       |
  | :-----------: | :------------------: |
  | int1 -eq int2 |    int1 等于 int2    |
  | int1 -ge int2 | int1 大于或等于 int2 |
  | int1 -gt int2 |    int1 大于 int2    |
  | int1 -le int2 | int1 小于或等于 int2 |
  | int1 -lt int2 |    int1 小于 int2    |
  | int1 -ne int2 |   int1 不等于 int2   |

  在使用整数操作符时，将变量的值视为整数的是 test 命令，而非 shell，因为无论 shell 变量的类型是什么，都能够进行比较

  *常用的test文件操作符*

  | 操作符  |      为真条件       |
  | :-----: | :-----------------: |
  | -d file |   file 是一个目录   |
  | -e file |      file 存在      |
  | -f file | file 是一个普通文件 |
  | -r file |  file 可由进程读取  |
  | -s file |   file 不是空文件   |
  | -w file |  file 可有进程写入  |
  | -x file |   file 是可执行的   |
  | -L file | file 是一个符号链接 |

  *逻辑操作符*

  `!` 取反，`-a` 与，`-o` 或

  在 test 表达式中利用括号来根据需要改变求值顺序，要对括号本身转义，括号两边必须有空格。test 要求条件语句中的每一个元素都是独立的

  ```shell
  [ \( "$count" -ge 0 \) -a \("$count" -lt 10 \) ]
  ```

* exit

  shell 内建的 exit 命令可以立即终止 shell 程序的执行

  ```shell
  exit n
  ```

  n 是状态码，如果未指定，则使用在 exit 之前最后执行那条命令的退出状态

##### 调试选项

要调试 shell 可以在程序正常的调用（名字及参数）之前输入 `sh -x` 以跟踪器执行过程。在该模式下，命令在执行的同时会被打印在终端中，并在其之前加上一个 `+` 号

