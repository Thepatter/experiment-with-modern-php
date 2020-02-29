### Spring Boot Web

#### 简单接口

##### Spring Mvc

###### 加入 web 依赖

创建项目后，在项目的 pom 文件中加入 web 依赖

```xml
<dependency>
	<groupId>org.springframework.boot</groupId>
	<artifactId>spring-boot-starter-web</artifactId>
</dependency>
```

###### controller

新建 Controller，在类上加入注解 @RestController（这是 Spring 4.0 版本之后的一个注解，功能相当于 @Controller 与 @ResponseBody 两个注解功能之和）

```java
@RestController
public class HelloController {
    @GetMapping("/hello")
    public String hello() {
        return "Hello, first Spring Boot Web Project!";
    }
}
```

##### Webflux

###### mvn 依赖

```xml
<dependency>
    <groupId>org.springframework.boot</groupId>
    <artifactId>spring-boot-starter-webflux</artifactId>
</dependency>
```

###### controller

* 使用注解方式

  ```java
  @RestController
  @RequestMapping("/users")
  public class MyFluxController {
      @GetMapping("/{user}")
      public Mono<User> getUser(@PathVariable int user) {
          return Mono.just(User.USERS.get(user));
      }
      @GetMapping("/list/{page}")
      public Mono<List<User>> index(@PathVariable int page) {
          int pageSize = 3;
          int startId = (page - 1) * 3 + 1;
          List<User> users = new LinkedList<>();
          for (int i = startId; i < startId + pageSize; i++) {
              users.add(User.USERS.get(i));
          }
          return Mono.just(users);
      }
  }
  
  ```

* 函数式

#### 页面模板

Spring Boot 包括以下模板引擎的自动配置文件，在默认配置下使用这些模板引擎之一时，将从 src/main/resources/templates 中自动提取模板，如果尽量避免使用 jsp

##### Thymeleaf

是 Spring boot 官方推荐使用的模板框架。 

###### mvn 依赖

```xml
<dependency>
	<groupId>org.springframework.boot</groupId>
	<artifactId>spring-boot-starter-thymeleaf</artifactId>
</dependency>
```

https://www.cnblogs.com/itdragon/archive/2018/04/13/8724291.html

##### FreeMarker

###### mvn

```xml
<dependency>
	<groupId>org.springframework.boot</groupId>
	<artifactId>spring-boot-starter-freemarker</artifactId>
</dependency>
```

##### Groovy

##### Mustache

##### JSP

###### mvn

```xml
<dependency>
	<groupId>org.apache.tomcat.embed</groupId>
	<artifactId>tomcat-embed-jasper</artifactId>
</dependency>
<dependency>
	<groupId>javax.servlet</groupId>
	<artifactId>jstl</artifactId>
</dependency>
```

##### WebJars

整合前端框架，默认映射为 src/main/recources/static 文件夹下新建 html。

#### 国际化

#### 文件

##### 上传

```java
@PostMapping("/uploads")
public JsonData uploads(HttpServletRequest request) {
    List<MultipartFile> files = ((MultipartHttpServletRequest) request).getFiles("file");
    String[] filesUpload = new String[files.size()];
    File[] uploadFiles = new File[files.size()];
    MultipartFile singleFile;
    for (int i = 0; i < files.size(); i++) {
        singleFile = files.get(i);
        if (singleFile.isEmpty()) {
            return new JsonData(1, "文件不能为空");
        }
        File currentUploadFile = new File(filePath + singleFile.getOriginalFilename());
        try {
            singleFile.transferTo(currentUploadFile);
            uploadFiles[i] = currentUploadFile;
            filesUpload[i] = currentUploadFile.getAbsolutePath();
        } catch (IOException e) {
            for (File file: uploadFiles) {
                file.delete();
            }
            return new JsonData(1, "上传失败", e.getMessage());
        }
    }
    return new JsonData(0, "上传成功", filesUpload);
}
```

##### 下载

```java
@GetMapping("/download/{file}")
public JsonData download(HttpServletResponse response, @PathVariable String file) {
    if (file == null) {
        return new JsonData(-1, "下载文件不能为空");
    }
    File downFile = new File(filePath + file);
    if (!downFile.exists()) {
        return new JsonData(-1, "文件不存在");
    }
    response.setContentType("application/force-download");
    response.addHeader("Content-Disposition", "attachment;fileName=" + file);
    try {
        try(BufferedInputStream fileInput =  new BufferedInputStream(new FileInputStream(downFile));
            OutputStream outputStream = response.getOutputStream()) {
            byte[] buffer = new byte[1024];
            int i = fileInput.read(buffer);
            while (i != -1) {
                outputStream.write(buffer, 0, i);
                i = fileInput.read(buffer);
            }
            return new JsonData(0, "下载成功", downFile);
        }
    } catch (IOException e) {
        e.printStackTrace();
    }
    return new JsonData(1, "下载失败");
}
```

