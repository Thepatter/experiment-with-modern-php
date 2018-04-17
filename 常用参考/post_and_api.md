### POSTMAN 第三方黑盒测试

postman 提供了 `pm.test` 方法，相当于一个测试用例，第一个参数是执行正确后的提示文字，第二个参数是个闭包，执行我们的断言。

```
pm.test("响应状态码正确", function () {
    pm.response.to.have.status(201);
})

pm.test("接口响应数据正确", function () {
    pm.expect(pm.response.text()).to.include("id");
    pm.expect(pm.response.text()).to.include("title");
    pm.expect(pm.response.text()).to.include("body");
    pm.expect(pm.response.text()).to.include("user_id");
    pm.expect(pm.response.text()).to.include("category_id");
})
```

第一个测试用例，判断响应的状态码 `pm.response.to.have.status(201)` 断言响应的状态码事 201

第二个测试用例，判断响应数据 `pm.expect(pm.response.text()).to.include("")` 断言响应数据中一定会包含某个字段

### API 文档

Apizza 在线 API 管理工具，界面及使用方式与 postman 类似。支持导入 postman 的 collection 文件