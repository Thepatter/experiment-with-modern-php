### Git 常用命令

git push 主机名 本地分支 远程分支 推送本地分支到远程分支

git remote -v 查看主机

git push [默认分支] 推送到默认分支

git clone url [-b 分支] 克隆选择分支
`git clone git@git.oschina.net:tenghuaguoji/wine.git -b feature/php/api win` 克隆 feature/php/api 分支

#### 添加提交

添加到缓存区

`git add -A`添加所有文件到缓存区

`git add *`添加文件到缓存区

提交到 HEAD

`git commit -m "代码提交信息"`

#### 推送改动

`git push origin master` 可以把master换成任何分支

**将仓库连接到某个远程服务器**`git remote add origin <server>`

#### 分支

`git checkout -b feature_x` 创建一个 feature_x 的分支，并切换过去

`git checkout master` 切换回主分支

`git branch -d feature_x` 删除分支 feature_x

`git branch -a` 查看所有分支

`git push origin <branch>` 推送到远程分支

#### 更新与合并

`git pull` 更新本地仓库至最新改动
`git pull origin feature/php/wechat` 拉取远程主机`feature/php/wechat`分支

`git merge <branch>` 合并分支

在工作目录中获取（fetch)并合并（merge）远端的改动。要合并其他分支到当前分支。执行 `git merge <branch>` 两种情况下，git 都会尝试区自动合并改动。自动合并失败则导致冲突 （conflicts）,手动修改文件来解决冲突。改动完后，执行 `git add <filename>`标记为合并成功。在合并改动前，用 `git diff <soure_branch> <target_branch>`命令查看差别。

#### 标签

在软件发布时创建标签。`git tag 1.0.0 1b2e1d63ff`  1b2e1d63ff 是想要标记的提交 ID 的前10位字符。使用 `git log`获取提交 ID

#### 替换本地改动、

`git checkout — <filename>` 此命令会使用 HEAD 中的最新内容替换工作目录中的文件。已添加到缓存区的改动，以及新文件。不受影响。

丢弃所有本地改动与提交，到服务器上获取最新的版本并将你本地分支指向它

`git fetch origin` `git reset --hard origin/master`
#### git回滚历史记录
`git log` 会显示 `git commit` 的历史记录
回滚历史记录 `git reset --hard 8ff24a6803173208f3e606e32dfcf82db9ac84d8` 回滚到历史提交,可以是输入前10位记录

#### 使用 .gitignore 文件忽略文件
在不需要加入版本控制的地方建立 `.gitignore` 文件来配置，一个文件或一个文件夹占一行,语法如下
```
.idea
.gitignore
```