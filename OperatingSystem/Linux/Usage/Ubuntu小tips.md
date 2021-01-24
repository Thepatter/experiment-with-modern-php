## ubuntu 一些小 tips

### 修改默认编辑器为 vim
```shell
sudo update-alternatives --config editor
```

### 开启 cron 日志

```shell
# 修改 rsyslog
sodo vim /etc/rsyslog.d/50-default.conf
# 取消 cron 注释
cron.* /var/log/cron.log
# 重启 rsyslog
sudo service rsyslog restart
# 重启 crontab
sudo service cron restart
# sysstat 软件包
sudo apt-get install -y sysstat
```

##### server 运行图形界面

###### 安装配置

```
# xfce4 是桌面，xrdp 模拟器，slim 输出管理程序
sudo apt install xfce4 xrdp slim
```

###### 配置中文环境

```
# 安装中文语言包
sudo apt-get install  language-pack-zh-han*
# 运行语言支持检查
sudo apt install $(check-language-support)
修改配置文件
vim /etc/default/local
# 新增内容
LANG="zh_CN.UTF-8"
LANGUAGE="zh_CN:zh"
LC_NUMERIC="zh_CN"
LC_TIME="zh_CN"
LC_MONETARY="zh_CN"
LC_PAPER="zh_CN"
LC_NAME="zh_CN"
LC_ADDRESS="zh_CN"
LC_TELEPHONE="zh_CN"
LC_MEASUREMENT="zh_CN"
LC_IDENTIFICATION="zh_CN"
LC_ALL="zh_CN.UTF-8"
# 修改环境文件
vim /etc/environment
# 新增
LANG="zh_CN.UTF-8"
LANGUAGE="zh_CN:zh"
LC_NUMERIC="zh_CN"
LC_TIME="zh_CN"
LC_MONETARY="zh_CN"
LC_PAPER="zh_CN"
LC_NAME="zh_CN"
LC_ADDRESS="zh_CN"
LC_TELEPHONE="zh_CN"
LC_MEASUREMENT="zh_CN"
LC_IDENTIFICATION="zh_CN"
LC_ALL="zh_CN.UTF-8"
```

