### Git

#### 基本使用

##### 架构

###### 术语

* committed

    数据已提交到本地仓库，commit 命令会提交到本地仓库 head

* modified

    已修改，修改了文件，但未保存到数据库

* staged

    已暂存，对一个已修改文件做了标记，使之包含在下次提交的快照中，add 命令会将文件提交到暂存区

* 仓库目录

    用 Git 用来保存项目的元数据和对象数据的地方

* 工作目录

    是对项目的某个版本独立提取出来的内容。这些从 Git 仓库的压缩数据库中提取出来的文件，放在磁盘上修改或使用

* 暂存区域

    是一个文件，保存了下次提交的文件列表信息，一般在 Git 仓库目录中。也称为索引

* 文件状态

    status 命令添加 -s 参数时，文件前面标记含义：

    * ??

        新添加未跟踪

    * A

        新添加到暂存区

    * M

        修改过（出现在有右边的 M 表示该文件被修改了但是还没有放入暂存区，出现在靠左边的 M 表示该文件被修改了并放入了暂存区）

    * R

        重命名

    * D

        删除

###### 工作流

基本的 Git 工作流

1. 在工作目录中修改文件
2. 暂存文件，将文件的快照放入暂存区域
3. 提交更新，将快照永久性存储到 Git 仓库目录

##### 常用操作

###### 移除文件

要从 Git 中移除某个文件，必须要从已跟踪文件清单中移除（从暂存区域移除），然后提交。可以使用 `git rm` 命令。这样即可不出现在未跟踪文件清单中。下一次提交时，该文件就不再纳入版本管理了。如果删除之前修改过并且已经放到暂存区域的话，则必须要用强制删除选项 `-f` 。（这是一种安全特性，用于防止误删还没有添加到快照的数据，这样的数不能被 Git 恢复）

如果想把文件从 `git` 仓库中删除（亦从暂存区域移除），但仍然希望保留在当前工作目录中，即（想让文件保留在磁盘中，但是并不想让 Git 继续跟踪）使用 `--cached` 选项

```shell
git rm --cached README
```

`git rm` 后面可以使用 `glob` 模式。

###### 提交历史

```shell
# 显示最近两次提交的差异
git log -p -2
# --stat 选项在每次提交的下面列出所有被修改过的文件
git log --stat
# --pretty 选项可指定不同于默认格式的方式展示提交历史。这个选项有一些内建的子选项。如 oneline 将每个提交放在一行显示。还有 short，full，fuller, format 选项可以定制要显示的记录格式
git log --pretty=oneline
git log --pretty=format: "%h - %an, %ar : %s"
```

###### 撤销操作

```shell
# 将暂存区中的文件提交。如果自上次提交以来未做任何修改，那么快照保持不变，修改的只是提交信息
git commit --amend
# 取消暂存文件
git reset HEAD <file>
# 撤销对文件的修改，还原成上次提交的样子
git checkout -- <file>
```

###### 忽略文件

创建一个 .gitignore 文件，列出要忽略的文件模式。.gitignore 的格式规范如下：

* 所有空行或以 # 开头的行都会被 Git 忽略
* 可以使用标准的 glob 模式匹配
* 匹配模式可以以  (/) 开头放置递归 
* 匹配模式可以以 (/) 结尾指定目录
* 要忽略指定模式以外的文件或目录，可以在模式前加 ! 取反

星号（`*`）匹配零个或多个任意字符；`[abc]`匹配任何一个列在方括号中的字符（这个例子要么匹配一个 a，要么匹配一个 b，要么匹配一个 c）；问号（`?`）只匹配一个任意字符；如果在方括号中使用短划线分隔两个字符，表示所有在这两个字符范围内的都可以匹配（比如 `[0-9]` 表示匹配所有 0 到 9 的数字）。 使用两个星号（`*`) 表示匹配任意中间目录，比如 `a/**/z` 可以匹配 `a/z` , `a/b/z` 或 `a/b/c/z` 等。

```shell
# 使 Git 忽略已经跟踪的文件, 即使已经更改了文件，用 git status 也不会看见文件已经更改
git update-index --assume-unchanged [files]
# 取消忽略文件
git update-index --no-assume-unchanged [files]
```

###### 加速克隆

```shell
# 代理
git config --global http.proxy 'socks5://127.0.0.1:1080'
git config --global https.proxy 'sock5://127.0.0.1:1080'
# 配置缓冲
git config --global http.postBuffer 524288000
```

##### 标签

Git 可以给历史中的某一个提交打上标签，比较有代表性的是人们会使用这个功能来标记发布结点（v1.0)

###### 操作

```shell
# 列出标签
git tag -l
# 查找标签
git tag -l 'v1.8.5*'
```

###### 创建标签

Git 使用两种主要类型的标签：

* 轻量标签（lightweight）

    一个轻量标签很像一个不会改变的分支，它只是一个特定提交的引用，本质上是将提交校验和存储到一个文件中，没有保存任何其他信息

    ```shell
    git tag v1.1
    ```

* 附注标签（annotated）

    附注标签是存储在 Git 数据库中的一个完整对象。它们是可以被校验的；其中包含打标签者的名字，电子邮件地址，日期时间；还有一个标签信息；并且可以使用 GNU Privacy Guard（GPG）签名与验证。

    ```shell
    # -m 选项指定了一条会存储在标签中的信息。如果没有为附注标签指定一条信息，Git 会运行编辑器要求输入信息
    git tag -a v1.4 -m 'my version 1.4'
    ```

通常建议创建附注标签，这样可以拥有以上信息；但是如果只是想用一个临时的标签，或者因为某些原因不想要保存那些信息，轻量标签也是可用的

###### 后期打标签

可以对过去的提交打标签，在标签末尾指定校验和

```shell
git tag -a v1.2 9fceb02
```

###### 共享标签

默认情况下，git push 命令并不会传送标签到远程仓库服务器上。在创建完标签后必须显示推送标签到共享服务器上。这个过程就像共享远程分支一样

```shell
# git push origin [tagname]
git push origin v1.5
# 如果相应一次推送很多标签，使用 --tags 选项的 git push 命令
git push origin --tags
# 拉取标签
git pull origin --tags
```

###### 删除标签

```shell
# 删除本地
git tag -d <tagname>
# 删除远程
git push origin --delete <tagname>
# 上述命令不会从任何远程仓库中移除这个标签，须用 git push <remote>:refs/tags/<tagname> 来更新远程仓库
git push origin:refs/tags/v1.4-lw
```

###### 检出标签

如果想查看某个标签指向的文件版本，可以使用 git checkout 

但这会使仓库处于『分离头指针（detached HEAD)』状态。<u>在分离头指针状态下，如果做了某些更改然后提交它们，标签不会发生变化，但你的新提交将不属于任何分支，并且无法访问，除非确切的提交哈希</u>

#### 分支

##### 本地分支

###### 内部操作

当使用  git commit 进行提交操作时：

1. Git 会先计算每一个子目录的校验和，然后在 Git 仓库中将这些校验和保存为树对象。
2. Git 便会创建一个提交对象，它除了包含上面提到的那些信息外，还包含指向这个树对象的指针。如此一来，Git 就可以在需要的时候重现此次保存的快照

Git 的分支，其实本质上仅仅是指向提交对象的可变指针。 Git 的默认分支名字是 master。 在多次提交操作之后，你其实已经有一个指向最后那个提交对象的 master 分支。 它会在每次的提交操作中自动向前移动。

Git 的 master 分支并不是一个特殊分支。 它就跟其它分支完全没有区别。 之所以几乎每一个仓库都有 master 分支，是因为 git init 命令默认创建它

###### 分支操作

```shell
# 创建分支，特殊指针 HEAD ，指向当前所在的本地分支
git branch testing
# 分支切换，此时 HEAD 指针指向 testing 分支
git checkout <branch>
# 查看分叉历史，会输出提交历史，各个分支的指向以及分支分叉情况
git log --oneline --decorate --graph --all
# 分支合并
git checkout master
git merge <branch-name>
# 合并某个分支上的单个 commit
git cherry-pick <commit-hash>
```

###### 分支管理

```shell
# 列出所有分支
git branch
# 查看每一个分支最后一次提交
git branch -v
# --merged 和 --no-merged 可以过滤这个列表中已经合并或尚未合并到当前分支的分支
# 查看那些分支已合并到当前分支
git branch --merged
# 查看所有包含未合并工作的分支
git branch --no-merged
# 删除分支
git branch -d testing
```

##### 远程分支

###### 远程仓库分支

远程引用是对远程仓库的引用（指针），包括分支、标签。

远程仓库名字 origin 在 Git 中并没有任何特别的含义一样。 origin 是运行 git clone 时默认的远程仓库名字。 如果运行 git clone -o booyah，那么你默认的远程分支名字将会是 booyah/master。

```shell
# 来显式地获得远程引用的完整列表
git ls-remote <remote-name>
# 查看远程分支详情
git remote show (remote)
```

###### 推送

本地的分支并不会自动与远程仓库同步 - 必须显式地推送想要分享的分支

```shell
# 推送本地的 serverfix 分支来更新远程仓库上的 serverfix 分支
git push origin serverfix
# 推送本地的 serverfix 分支，将其作为远程仓库的 <serverfix> 分支
git push origin serverfix:<serverfix>
# 如果想在自己的 serverfix 分支上工作，可以将其建立在远程跟踪分支上
git checkout -b serverfix origin/serverfix
```

###### 跟踪分支

从一个远程跟踪分支检出一个本地分支会自动创建所谓的跟踪分支（它跟踪的分支叫做上游分支）

跟踪分支是与远程分支有直接关系的本地分支。如果在一个跟踪分支上输入 git pull，Git 就能自动识别去哪个服务器上抓取，合并到那个分支。当克隆一个仓库时，它通常会自动地创建一个跟踪  origin/master 的  master 分支

```shell
# 跟踪远程分支
git checkout -b [local-branch] [remotename]/[branch]
# 等效
git checkout --track origin/serverfix
# 设置与远程分支不同地名字
git checkout -b local-branch-other-name origin/serverfix
# 设置已有本地分支跟踪拉取的远程分支，或修改正在跟踪地上游分支，使用 -u 或 --set-upstream-to 选项
git branch -u origin/serverfix
# 可以通过 @{u} 或 @{upstream} 引用跟踪分支
git merge @{u} # 等价于 git merge origin/master
# 查看设置的所有跟踪分支，数字的值来自于你从每个服务器上最后一次抓取的数据，获取最新信息先 git fetch --all
git branch -vv
```

分支跟踪状态

* ahead

    本地有提交还未推送到服务器上

* behind

    落后服务器，即服务器有提交还未合并

###### 拉取

当 fetch 命令从服务器上抓取本地没有的数据时，不会修改工作目录内容。需要手动合并。

pull 在大多数情况下是 fetch 紧接着 merge 命令（pull 会查找当前分支所跟踪的服务器与分支，从服务器上抓取数据然后尝试合并入那个远程分支）

```shell
# 拉取合并
git pull origin master
# 等价
git fetch origin master
git merge origin/master
# 删除远程分支，即从服务器上移除这个指针
git push origin --delete serverfix
```

##### 变基

###### 原理

首先找到这个两个分支（当前分支 experiment，变基操作的目标基底分支 master）的最近共同祖先 C2，然后对比当前分支相对于该祖先的历次提交，提取相应的修改并存为临时文件，然后将当前分支指向目标基底 C3，最后以此将之前另存为临时文件的修改依序应用

一般这样做的目的是为了确保在向远程分支推送时能保持提交历史的整洁。（如向某个其他人维护的项目贡献代码时。在这种情况下，首先在自己的分支里进行开发，当开发完成时需要先将你的代码变基到 origin/master 上，然后再向主项目提交修改。这样的话，该项目的维护者就不再需要进行整合工作，只需要快进合并即可）

在 Git 中整合来自不同分支的修改主要有两种方法：merge 和 rebase

###### 操作

**变基：**使用 release 命令将提交到某一个分支上的所有修改都移至另一个分支上

```shell
git checkout experiment
# 变基
git rebase master
# 快进合并
git checkout master
git merge experiment
```

两种整合方法的最终结果没有任何区别，但是变基使得提交历史更加整洁。 你在查看一个经过变基的分支的历史记录时会发现，尽管实际的开发工作是并行的，但它们看上去就像是串行的一样，提交历史是一条直线没有分叉

* 快进合并

    由于当前 master 分支所指向的提交是你当前提交（有关 hotfix 的提交）的直接上游，所以 Git 只是简单的将指针向前移动。 

    换句话说，当你试图合并两个分支时，如果顺着一个分支走下去能够到达另一个分支，那么 Git 在合并两者的时候，只会简单的将指针向前推进（指针右移），因为这种情况下的合并操作没有需要解决的分歧——这就叫做快进（fast-forward）。

###### 变基的风险

**不要对在仓库外有副本的分支执行变基**，只对尚未推送或分享给别人的本地修改执行变基操作清理历史，从不对已推送至别处的提交执行变基操作，这样，你才能享受到两种方式带来的便利。

只要你把变基命令当作是在推送前清理提交使之整洁的工具，并且只在从未推送至共用仓库的提交上执行变基命令，就不会有事。 

#### 服务器上 Git

一个远程仓库通常只是一个裸仓库（*bare repository*）— 即一个没有当前工作目录的仓库。 因为该仓库仅仅作为合作媒介，不需要从磁盘检查快照；存放的只有 Git 的资料。 简单的说，裸仓库就是你工程目录内的 .git 子目录内容，不包含其他资料

##### 协议

###### 本地协议

本地协议（local protocol），其中的远程版本库就是硬盘内的另一个目录。可以从本地版本库克隆（clone），推送（push）以及拉取（pull）

```shell
# 克隆一个本地版本库
git clone /opt/git/project.git
# 等同于
git clone file:///opt/git/project.git
```

如果在 URL 开头明确的指定 file:///，那么 Git 的行为会略有不同。如果仅指定路径，Git 会尝试使用硬链接或者直接复制所需要的文件。如果指定 file:///，Git 会触发平时用于网络传输的进程

```shell
# 增加一个本地版本库到现有的 Git 项目
git remote add local_proj /opt/git/project.git
```

###### HTTP 协议

* Smart HTTP 协议

    『智能』HTTP 协议的运行方式和 SSH 及 Git 协议类似，只是运行在标准的 HTTP/S 端口上并且可以使用各种 HTTP 验证机制，这意味着使用起来会比 SSH 协议简单的多，比如可以使用 HTTP 协议的用户名／密码的基础授权，免去设置 SSH 公钥。

    智能 HTTP 协议或许已经是最流行的使用 Git 的方式了，它即支持像 git:// 协议一样设置匿名服务，也可以像 SSH 协议一样提供传输时的授权和加密。 而且只用一个 URL 就可以都做到，省去了为不同的需求设置不同的 URL。 如果你要推送到一个需要授权的服务器上（一般来讲都需要），服务器会提示你输入用户名和密码。 从服务器获取数据时也一样。

* Dumb HTTP 协议

    如果服务器没有提供智能 HTTP 协议的服务，Git 客户端会尝试使用更简单的『哑』HTTP 协议。 哑 HTTP 协议里 web 服务器仅把裸版本库当作普通文件来对待，提供文件服务。 哑 HTTP 协议的优美之处在于设置起来简单。 基本上，只需要把一个裸版本库放在 HTTP 根目录，设置一个叫做 post-update 的挂钩就可以了（见 [Git 钩子](https://git-scm.com/book/zh/v2/ch00/r_git_hooks)）。 此时，只要能访问 web 服务器上你的版本库，就可以克隆你的版本库。 下面是设置从 HTTP 访问版本库的方法

    ```shell
    $ cd /var/www/htdocs/
    $ git clone --bare /path/to/git_project gitproject.git
    $ cd gitproject.git
    $ mv hooks/post-update.sample hooks/post-update
    $ chmod a+x hooks/post-update
    ```

    这样就可以了。 Git 自带的 post-update 挂钩会默认执行合适的命令（git update-server-info），来确保通过 HTTP 的获取和克隆操作正常工作。 这条命令会在你通过 SSH 向版本库推送之后被执行；然后别人就可以通过类似下面的命令来克隆：

    这里我们用了 Apache 里设置了常用的路径 /var/www/htdocs，不过你可以使用任何静态 web 服务器 —— 只需要把裸版本库放到正确的目录下就可以。 

###### SSH 协议

架设 Git 服务器时常用 SSH 协议作为传输协议。 因为大多数环境下服务器已经支持通过 SSH 访问 —— 即使没有也很容易架设。 SSH 协议也是一个验证授权的网络协议；并且，因为其普遍性，架设和使用都很容易。

```shell
# 通过 SSH 协议克隆版本库，指定一个 ssh:// 的 URL
git clone ssh://user@server/project.git
# 或者简短的 scp 式用法，也可以不指定用户，Git 会使用当前登录的用户名。
git clone user@server:project.git
```

###### Git 协议

这是包含在 Git 里的一个特殊的守护进程；它监听在一个特定的端口（9418），类似于 SSH 服务，但是访问无需任何授权。 

要让版本库支持 Git 协议，需要先创建一个 git-daemon-export-ok 文件 —— 它是 Git 协议守护进程为这个版本库提供服务的必要条件 —— 但是除此之外没有任何安全措施。 要么谁都可以克隆这个版本库，要么谁也不能。 这意味着，通常不能通过 Git 协议推送。 由于没有授权机制，一旦你开放推送操作，意味着网络上知道这个项目 URL 的人都可以向项目推送数据。 不用说，极少会有人这么做。

##### 在服务器上搭建仓库

###### 使用 SSH 协议

1. 在开始架设 Git 服务器前，需要把现有仓库导出为裸仓库——即一个不包含当前工作目录的仓库。 这通常是很简单的。 为了通过克隆你的仓库来创建一个新的裸仓库，需要在克隆命令后加上 --bare 选项。 按照惯例，裸仓库目录名以 .git 结尾，就像这样：

    ```shell
    git clone --bare my_project my_project.git
    ```

    或者新建裸仓库

    ```
    git init --bare sample.git
    ```

2. 把裸仓库放到服务器上

    ```shell
    scp -r my_project.git user@git.example.com:/opt/git
    ```

    此时，其他通过 SSH 连接这台服务器并对 /opt/git 目录拥有可读权限的使用者，通过运行以下命令就可以克隆你的仓库。

    ```shell
    git clone user@git.example.com:/opt/git/my_project.git
    ```

    如果一个用户，通过使用 SSH 连接到一个服务器，并且其对 /opt/git/my_project.git 目录拥有可写权限，那么他将自动拥有推送权限。

    如果到该项目目录中运行 git init 命令，并加上 --shared 选项，那么 Git 会自动修改该仓库目录的组权限为可写。

    ```shell
    git init --bare --shared
    ```

这的确是架设一个几个人拥有连接权的 Git 服务的全部——只要在服务器上加入可以用 SSH 登录的帐号，然后把裸仓库放在大家都有读写权限的地方。 你已经准备好了一切，无需更多。

如果需要团队里的每个人都对仓库有写权限，又不能给每个人在服务器上建立账户，那么提供 SSH 连接就是唯一的选择了。 我们假设用来共享仓库的服务器已经安装了 SSH 服务，而且你通过它访问服务器。

有几个方法可以使你给团队每个成员提供访问权

* 第一个就是给团队里的每个人创建账号，这种方法很直接但也很麻烦。 或许你不会想要为每个人运行一次 adduser 并且设置临时密码。

* 第二个办法是在主机上建立一个 *git* 账户，让每个需要写权限的人发送一个 SSH 公钥，然后将其加入 git 账户的 ~/.ssh/authorized_keys 文件。 这样一来，所有人都将通过 *git* 账户访问主机。 这一点也不会影响提交的数据——访问主机用的身份不会影响提交对象的提交者信息。

    ```shell
    # 创建 git 用户并为 git 用户配置服务器端的 ssh 访问
    sudo adduser git
    su git
    cd 
    mkdir .ssh && chmod 700 .ssh
    touch .ssh/authorized_keys && chmod 600 .ssh/authorized_keys
    # 需要读写权限的用户生成公钥
    ssh-keygen
    # 用户将各自的公钥发送给 Git 服务器管理员
    cat /tmp/id_rsa.john.pub >> ~/.ssh/authorized_keys
    cat /tmp/id_rsa.josie.pub >> ~/.ssh/authorized_keys
    # 服务器初始化远程仓库
    git init --bare
    # 初始化本地仓库并推送
    git init
    git add .
    git commit -m "initial commit"
    git remote add origin git@gitserver:/opt/git/project.git
    git push origin master
    ```
    
* 另一个办法是让 SSH 服务器通过某个 LDAP 服务，或者其他已经设定好的集中授权机制，来进行授权。 只要每个用户可以获得主机的 shell 访问权限，任何 SSH 授权机制你都可视为是有效的。



