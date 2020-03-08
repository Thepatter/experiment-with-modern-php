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

#### 检验输入

Spring 支持 java 的 Bean  检验 API（Bean Validation API，JSR-303）。能声明检验规则，而不必在应用程序中显式编写声明逻辑。

Spring boot web starter 传递依赖会自动将 Hibernate Validation 添加进来。

Validation API 提供了可以添加到域对象上的注解，以声明校验。

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

在运行时，Spring Boot 的自动配置功能会发现 Thymeleaf 在类路径中，因此会为 Spring MVC 创建支撑 Thymeleaf 视图的 bean。

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

#### Rest 端点

Spring 提供了 RestTemplate 能够作为 Rest 端点请求

*RestTemplate中独立操作*

|       方法        |                             描述                             |
| :---------------: | :----------------------------------------------------------: |
|     delete()      |          在特定的 URL 上对资源进行 HTTP DELETE 操作          |
|    exchange()     | 在 URL 上执行特定的 HTTP 方法，返回包含对象的 ResponseEntity，该对象从响应体中映射得到 |
|     execute()     | 在 URL 上执行特定的 HTTP 方法，返回一个从响应体映射得到的对象 |
|  getForEntity()   | 发送一个 HTTP GET 请求，返回 ResponseEntity 包含了响应体所映射成的对象 |
|  getForObject()   |     发送一个 HTTP GET 请求，返回的请求体将映射为一个对象     |
| headForHeaders()  |   发送 HTTP HEAD 请求，返回包含特定资源 URL 的 HTTP 头信息   |
| optionsForAllow() |     发送 HTTP OPTIONS 请求，返回特定 URL 的 Allow 头信息     |
| patchForObject()  |     发送 HTTP PATCH 请求，返回一个从响应体映射得到的对象     |
|  postForEntity()  | POST 数据到一个 URL，返回包含一个对象的 ResponseEntity，这个对象是从响应体中映射得到的 |
| postForLocation() |          POST 数据到一个 URL，返回新创建资源的 URL           |
|  postForObject()  |      POST 数据到一个 URL，返回根据响应体匹配形成的对象       |
|       put()       |                     PUT 资源到特定的 URL                     |

除了 TRACE 以外，RestTemplate 对每种标准的 HTTP 方法都提供了至少一个方法。除此之外，execute() 和 exchange() 提供了较低层的通用方法，可以进行任意的 HTTP 操作