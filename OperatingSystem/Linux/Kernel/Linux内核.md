### Linux 磁盘相关

#### 硬件装置在 Linux 中的文件名

在 Linux 系统中，每个装置都被当作一个文件

*常见装置对应文件名*

|        装置        |                          对应文件名                          |
| :----------------: | :----------------------------------------------------------: |
| SCSI/SATA/USB 硬盘 |                         /dev/sd[a-p]                         |
|        U 盘        |                  /dev/sd[a-p] 与 SATA 相同                   |
|      Virtl/O       |                  /dev/va[a-p] 用于虚拟机内                   |
|     软盘驱动器     |                         /dev/fd[0-7]                         |
|       打印机       | /dev/lp[0-2]「25 针打印机」，/dev/usb/lp[0-15] 「USE 界面」  |
|        鼠标        |    /dev/input/mouse[0-15] 通用，/dev/psaux 「PS/2 界面」     |
|    CDROM/DVDROM    | /dev/scd[0-1] 「通用」，/dev/sr[0-1] 「通用 CentOS 较常见」，/dev/cdrom 「当前 CDROM」 |
|       磁带机       | /dev/ht0 「IDE 界面」，/dev/st0 「SATA/SCSI 界面」，/dev/tape 「当前磁带」 |
|     IDE 硬盘机     |                 /dev/hd[a-p] 「旧系统专用」                  |

