## Hyper-V

### 创建虚拟机

* 使用 `Hyper-V Quick Create` 来快速创建

  1.选择一个操作系统或者使用本地安装源选择自己的操作系统

  2.使用自己的映射创建虚拟机，使用 `Local Installation Source`

  3.选择安装源，`.iso` 或 `.vhdx` 文件

  4.如果为 `linux` ，取消选择 “安全启动”选项

* 在 `Hyper-V 管理器` 进行配置

### 使用 PowerShell 管理虚拟机

#### 使用 `powerShell` 操作

返回 `Hyper-V` 命令列表

```powershell
Get-Command -Module hyper-v | Qut-GridView
```

获取帮助

```powershell
Get-Help Get-VM
```

#### 返回虚拟机列表

* 列出虚拟机列表

  `Get-VM`

* 返回已启动的虚拟机列表

  ```powershell
  Get-VM | where {$_.State -eq 'Running'}
  ```

* 列出所有处于关机状态的虚拟机

  ```powershell
  Get-VM | where {$_.State -eq 'Off'}
  ```

#### 启动或关闭虚拟机

* 启动特定虚拟机，运行附带虚拟机名称的以下命令

  ```powershell
  Start-VM -Name <virtual machine name>
  # eg
  start-vm -name ubuntu
  ```

  若要启动所有当前已关机的虚拟机，获取这些虚拟机的列表并将该列表通过管道传递到 `start-vm` 命令

* 关闭所有正在运行的虚拟机

  ```powershell
  # 关闭所有运行的 vm
  Get-VM | where {$_.State -eq 'Running'} | Stop-VM
  # 关闭单个
  Stop-vm -name ubuntu
  ```

#### 创建 VM 检查点

使用 `get-vm` 命令选择虚拟机，然后通过管道将该虚拟机传递到 `Checkpoint-VM` 命令，然后使用 `-SnapshotName` 为检查点命名。

```powershell
Get-VM -Name <VM Name> | Checkpoint-VM -SnapshotName <name for snapshot>
```

#### 创建新的虚拟机

在 `PowerShell` 集成脚本环境（ISE）中创建新的虚拟机。

简单示例

```powershell
$VMName = "VMNAME"

$VM = @{
   Name = $VMName 
   MemoryStartupBytes = 2147483648
   Generation = 2
   NewVHDPath = "C:\Virtual Machines\$VMName\$VMName.vhdx"
   NewVHDSizeBytes = 53687091200
   BootDevice = "VHD"
   Path = "C:\Virtual Machines\$VMName"
   SwitchName = (Get-VMSwitch).Name
}

New-VM @VM
```

### Hyper-V 备注

* 不能在 `Hyper-V` 虚拟机中运行 `poweroff` 命令，会导致系统卡死，并且必须重新进 `blos` 里进行引导

  



