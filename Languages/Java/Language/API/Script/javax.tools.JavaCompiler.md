*javax.tools.JavaCompiler*
```java
StandardJavaFileManager getStandardFileManager(DiagnosticListener<? super JavaFileObject> diagnosticListener, Locale locale, Charset charset);

// 获取该编译器的标准文件管理器，如果要使用默认的错误报告机制、locale 和字符集等参数，则可以提供 null

JavaCompiler.CompilationTaskgetTask(Writerout, JavaFileManagerfileManager, DiagnosticListener<? super JavaFileObject> diagnosticListener, Iterable<String> options, Iterable<String> classesForAnnotationProcessing, Iterable<? extends JavaFileObject> sourceFiles);

// 获取编译任务，在被调用时，该任务将编译给定的源文件。

```