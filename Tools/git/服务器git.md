## 服务器上的 Git

### 协议

一个远程仓库通常只是一个裸仓库（*bare repository*）— 即一个没有当前工作目录的仓库。 因为该仓库仅仅作为合作媒介，不需要从磁盘检查快照；存放的只有 Git 的资料。 简单的说，裸仓库就是你工程目录内的 `.git` 子目录内容，不包含其他资料

#### 本地协议

本地协议（local protocol），其中的远程版本库就是硬盘内的另一个目录。可以从本地版本库克隆（clone），推送（push）以及拉取（pull）

```shell
# 克隆一个本地版本库
git clone /opt/git/project.git
# 等同于
git clone file:///opt/git/project.git
```

如果在 `URL` 开头明确的指定 `file:///`，那么 Git 的行为会略有不同。如果仅指定路径，Git 会尝试使用硬链接或者直接复制所需要的文件。如果指定 `file:///`，Git 会触发平时用于网络传输的进程

```shell
# 增加一个本地版本库到现有的 Git 项目
git remote add local_proj /opt/git/project.git
```

#### HTTP 协议

##### Smart HTTP 协议

“智能” HTTP 协议的运行方式和 SSH 及 Git 协议类似，只是运行在标准的 HTTP/S 端口上并且可以使用各种 HTTP 验证机制，这意味着使用起来会比 SSH 协议简单的多，比如可以使用 HTTP 协议的用户名／密码的基础授权，免去设置 SSH 公钥。

智能 HTTP 协议或许已经是最流行的使用 Git 的方式了，它即支持像 `git://` 协议一样设置匿名服务，也可以像 SSH 协议一样提供传输时的授权和加密。 而且只用一个 URL 就可以都做到，省去了为不同的需求设置不同的 URL。 如果你要推送到一个需要授权的服务器上（一般来讲都需要），服务器会提示你输入用户名和密码。 从服务器获取数据时也一样。

##### Dumb HTTP 协议

如果服务器没有提供智能 HTTP 协议的服务，Git 客户端会尝试使用更简单的“哑” HTTP 协议。 哑 HTTP 协议里 web 服务器仅把裸版本库当作普通文件来对待，提供文件服务。 哑 HTTP 协议的优美之处在于设置起来简单。 基本上，只需要把一个裸版本库放在 HTTP 根目录，设置一个叫做 `post-update` 的挂钩就可以了（见 [Git 钩子](https://git-scm.com/book/zh/v2/ch00/r_git_hooks)）。 此时，只要能访问 web 服务器上你的版本库，就可以克隆你的版本库。 下面是设置从 HTTP 访问版本库的方法

```shell
$ cd /var/www/htdocs/
$ git clone --bare /path/to/git_project gitproject.git
$ cd gitproject.git
$ mv hooks/post-update.sample hooks/post-update
$ chmod a+x hooks/post-update
```

这样就可以了。 Git 自带的 `post-update` 挂钩会默认执行合适的命令（`git update-server-info`），来确保通过 HTTP 的获取和克隆操作正常工作。 这条命令会在你通过 SSH 向版本库推送之后被执行；然后别人就可以通过类似下面的命令来克隆：

这里我们用了 Apache 里设置了常用的路径 `/var/www/htdocs`，不过你可以使用任何静态 web 服务器 —— 只需要把裸版本库放到正确的目录下就可以。 

#### SSH 协议

架设 Git 服务器时常用 SSH 协议作为传输协议。 因为大多数环境下服务器已经支持通过 SSH 访问 —— 即使没有也很容易架设。 SSH 协议也是一个验证授权的网络协议；并且，因为其普遍性，架设和使用都很容易。

通过 SSH 协议克隆版本库，指定一个 `ssh://` 的 URL

```shell
git clone ssh://user@server/project.git
```

或者简短的 `scp` 式用法

```shell
git clone user@server:project.git
```

也可以不指定用户，Git 会使用当前登录的用户名。

#### Git 协议

接下来是 Git 协议。 这是包含在 Git 里的一个特殊的守护进程；它监听在一个特定的端口（9418），类似于 SSH 服务，但是访问无需任何授权。 要让版本库支持 Git 协议，需要先创建一个 `git-daemon-export-ok` 文件 —— 它是 Git 协议守护进程为这个版本库提供服务的必要条件 —— 但是除此之外没有任何安全措施。 要么谁都可以克隆这个版本库，要么谁也不能。 这意味着，通常不能通过 Git 协议推送。 由于没有授权机制，一旦你开放推送操作，意味着网络上知道这个项目 URL 的人都可以向项目推送数据。 不用说，极少会有人这么做。

### 在服务器上搭建 Git SSH

在开始架设 Git 服务器前，需要把现有仓库导出为裸仓库——即一个不包含当前工作目录的仓库。 这通常是很简单的。 为了通过克隆你的仓库来创建一个新的裸仓库，你需要在克隆命令后加上 `--bare` 选项。 按照惯例，裸仓库目录名以 .git 结尾，就像这样：

```shell
git clone --bare my_project my_project.git
```

或者新建裸仓库

```shell
git init --bare sample.git
```

把裸仓库放到服务器上

```shell
scp -r my_project.git user@git.example.com:/opt/git
```

此时，其他通过 SSH 连接这台服务器并对 `/opt/git` 目录拥有可读权限的使用者，通过运行以下命令就可以克隆你的仓库。

```shell
git clone user@git.example.com:/opt/git/my_project.git
```

如果一个用户，通过使用 SSH 连接到一个服务器，并且其对 `/opt/git/my_project.git` 目录拥有可写权限，那么他将自动拥有推送权限。

如果到该项目目录中运行 `git init` 命令，并加上 `--shared` 选项，那么 Git 会自动修改该仓库目录的组权限为可写。

```shell
git init --bare --shared
```

这的确是架设一个几个人拥有连接权的 Git 服务的全部——只要在服务器上加入可以用 SSH 登录的帐号，然后把裸仓库放在大家都有读写权限的地方。 你已经准备好了一切，无需更多。

如果需要团队里的每个人都对仓库有写权限，又不能给每个人在服务器上建立账户，那么提供 SSH 连接就是唯一的选择了。 我们假设用来共享仓库的服务器已经安装了 SSH 服务，而且你通过它访问服务器。

有几个方法可以使你给团队每个成员提供访问权。 第一个就是给团队里的每个人创建账号，这种方法很直接但也很麻烦。 或许你不会想要为每个人运行一次 `adduser` 并且设置临时密码。

第二个办法是在主机上建立一个 *git* 账户，让每个需要写权限的人发送一个 SSH 公钥，然后将其加入 git 账户的 `~/.ssh/authorized_keys` 文件。 这样一来，所有人都将通过 *git* 账户访问主机。 这一点也不会影响提交的数据——访问主机用的身份不会影响提交对象的提交者信息。

另一个办法是让 SSH 服务器通过某个 LDAP 服务，或者其他已经设定好的集中授权机制，来进行授权。 只要每个用户可以获得主机的 shell 访问权限，任何 SSH 授权机制你都可视为是有效的。

需要读写权限的用户生成公钥

```shell
ssh-keygen
```

用户将各自的公钥发送给 Git 服务器管理员。

为 git 用户配置服务器端的 `ssh` 访问

```shell
sudo adduser git
su git
cd 
mkdir .ssh && chmod 700 .ssh
touch .ssh/authorized_keys && chmod 600 .ssh/authorized_keys
```

导入需要访问仓库的用户的公钥

```shell
$ cat /tmp/id_rsa.john.pub >> ~/.ssh/authorized_keys
$ cat /tmp/id_rsa.josie.pub >> ~/.ssh/authorized_keys
$ cat /tmp/id_rsa.jessica.pub >> ~/.ssh/authorized_keys
```

创建新仓库

```shell
$ cd /opt/git
$ mkdir project.git
$ cd project.git
$ git init --bare
```

接着，John、Josie 或者 Jessica 中的任意一人可以将他们项目的最初版本推送到这个仓库中，他只需将此仓库设置为项目的远程仓库并向其推送分支。 请注意，每添加一个新项目，都需要有人登录服务器取得 shell，并创建一个裸仓库。 我们假定这个设置了 `git` 用户和 Git 仓库的服务器使用 `gitserver` 作为主机名。 同时，假设该服务器运行在内网，并且你已在 DNS 配置中将 `gitserver` 指向此服务器。那么我们可以运行如下命令（假定 `myproject` 是已有项目且其中已包含文件）

```shell
# on John's computer
$ cd myproject
$ git init
$ git add .
$ git commit -m 'initial commit'
$ git remote add origin git@gitserver:/opt/git/project.git
$ git push origin master
```

此时，其他开发者可以克隆此仓库，并推回各自的改动，步骤很简单：

```shell
$ git clone git@gitserver:/opt/git/project.git
$ cd project
$ vim README
$ git commit -am 'fix for the README file'
$ git push origin master
```

通过这种方法，你可以快速搭建一个具有读写权限、面向多个开发者的 Git 服务器。

需要注意的是，目前所有（获得授权的）开发者用户都能以系统用户 `git` 的身份登录服务器从而获得一个普通 shell。 如果你想对此加以限制，则需要修改 `passwd` 文件中（`git` 用户所对应）的 shell 值。

### 服务器上的 Git -Smart HTTP

