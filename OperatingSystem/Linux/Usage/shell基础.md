## shell 基础使用相关

#### 环境变量

* 查看当前环境变量

  `echo $PATH`

* 添加 PATH

  `export PATH=$PATH:<PATH1>:<PATH2>`

  ```shell
  vim .bash_profile # /etc/profile
  export PATH=$PATH:/usr/bin:/usr/sbin
  source .bash_profile
  ```

### 命令行历史

- `history`

  打印命令历史记录

- `history N`

  打印命令历史记录中的 N 个命令

- `history -c`

  清除命令历史记录

### 作业控制

* `jobs`

  列出当前 shell 中运行的作业，左侧的整数是作业编号，带加号的作业被当做默认作业。在使用 `fg` 和 `bg` 命令时，如果未在命令行指定任何作业编号的话，带加号的作业被当成作业控制命令的操作对象

* `&`

  放在命令行末尾的时候，让该命令作为后台作业运行

* `^Z`

  挂起当前（前台）作业。可以输入 `bg` 将命令放入后台运行或者输入 `fg` 使其在前台运行

* `suspend`

  `suspend` 将挂起当前的 shell

* `bg [%jobnumber]`

  让挂起的作业在后台运行。当不使用参数的时候，操作对象为最近挂起的作业。

* `fg [%jobnumber]`

  让挂起的作业或后台作业到前台运行。

### 模拟多shell

* `screen`

  `^A?` 		显示所有组合键命令

  `^A^C`		创建一个窗口

  `^A0, ^A1...^A9`			分别切换到窗口 0-9

  `^A'`			提示窗口号（0-9），然后切换到该窗口

  `^A^N`			以数字方式切换到下一个创建

  `^A^P`			以数字方式切换到上一个窗口

  `^A^A`			切换到最近使用的其他窗口

  `^A^W`			列出所有的窗口

  `^AN`			显示当前窗口号

  `^D`				终止当前 shell

  `^A \`



