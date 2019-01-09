## java.nio.file.Files

* `static Stream<String> lines(Path path)`

* `static Stream<String> lines(Path path, Charset cs)`

    产生一个流，它的元素是指定文件中的行，该文件中字符集为 `UTF-8`，或为指定的字符集