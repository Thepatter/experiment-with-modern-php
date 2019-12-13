### Git 常用命令

#### 基本使用

```bash
# 主机名 本地分支 远程分支 推送本地分支到远程分支
git push 
# 查看主机
git remote -v
# 推送到默认分支
git push <default>
# 克隆指定分支
git clone url [-b branch_name]
git clone git@git.oschina.net:tenghuaguoji/wine.git -b feature/php/api win
```

#### 添加提交

* 添加到缓存区

  ```bash
  # 添加所有文件
  git add -A
  git add .
  # 添加单个文件
  git add <file>
  ```

* 提交到 HEAD

  ```bash
  git commit -m "commit description"
  ```

* 推送

  ```bash
  # 添加仓库到服务器
  git remote add origin [server.git]
  # 推送到 master 分支
  git push origin master
  ```

#### 分支

```bash
# 创建分支并切换
git checkout -b [branch_name]
# 切换回主分支
git checkout master
# 删除分支
git branch -d [branch_name]
# 查看所有分支
git branch -a
# 推送到分支
git push origin [branch_name]
```

#### 更新与合并

```bash
# 拉取服务端
git fetch [origin]
# 合并
git merge [branch]
# 拉取合并
git pull [origin] [branch]
```

在工作目录中获取 `fetch` 并合并`merge` 远端的改动，要合并其他分支到当前分支。执行  `git merge <branch>` 两种情况下，git 都会尝试区自动合并改动。自动合并失败则导致冲突 （conflicts），手动修改文件来解决冲突。改动完后，执行 `git add <filename>`标记为合并成功。在合并改动前，用 `git diff <soure_branch> <target_branch>` 命令查看差别。

#### 标签

在软件发布时创建标签。`git tag 1.0.0 1b2e1d63ff`  `1b2e1d63ff` 是想要标记的提交 ID 的前10位字符。使用 `git log`获取提交 ID

#### 替换本地改动

```bash
# 使用 HEAD 中的最新内容替换工作目录中的文件,已添加到缓存区的改动，以及新文件不受影响。
git checkout — [filename]
# 丢弃所有本地改动与提交，到服务器上获取最新的版本并将本地分支指向它
git fetch origin
git reset --hard origin/master
```

#### git回滚历史记录

```bash
# 显示 commit 历史记录
git log
# 回滚
git reset --hard <tag>
```

#### 忽略文件
在不需要加入版本控制的地方建立 `.gitignore` 文件来配置，一个文件或一个文件夹占一行,语法如下
```
.idea
.gitignore
```

#### 使 Git 忽略已经跟踪的文件

```bash
# 即使已经更改了文件，用 `git status` 也不会看见文件已经更改
git update-index --assume-unchanged [files]
# 取消忽略文件
git update-index --no-assume-unchanged [files]
```

#### 加速克隆

```bash
# 代理
git config --global http.proxy 'socks5://127.0.0.1:1080'
git config --global https.proxy 'sock5://127.0.0.1:1080'
# 配置缓冲
git config --global http.postBuffer 524288000
```
