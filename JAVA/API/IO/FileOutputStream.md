## java.io.FileOutputStream

* `FileOutputStream` 类继承自 `OutputSteam` 类，用于向文件写入字节

* `FileOutputStream(File file)`

    从一个 `File` 对象构建一个 `FileOutputStream`
    
* `FileOutputStream(String filename)`

    从一个文件名创建一个 `FileOutputStream`
    
* `FileOutputStream(File file, boolean append)`

    如果 `append` 为 `true`, 将数据追加到已存在的文件中
    
* `FileOutputStream(String filename, boolean append)`

    如果 `append` 为 `true`, 将数据追加到已存在的文件中