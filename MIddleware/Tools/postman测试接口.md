### POSTMAN 第三方黑盒测试

postman 提供了 `pm.test` 方法，相当于一个测试用例，第一个参数是执行正确后的提示文字，第二个参数是个闭包，执行我们的断言

文档：https://www.getpostman.com/docs/v6/postman/scripts/test_examples

```json
// us pm.response.to.have
pm.test("response is ok", function () {
    pm.response.to.have.status(200);
})
// us pm.expect()
pm.test("environment to be production", function () {
    pm.expect(pm.environment.get("env")).to.equal("production");
})
// us response assertions
pm.test("response should be okay to process", function () {
    pm.response.to.not.be.error;
    pm.response.to.have.jsonBody("");
    pm.response.to.not.have.jsonBody("error");
})
// us pm.response.to.be*
pm.test("response must be valid and have a body", function () {
    pm.response.to.be.ok;
    pm.response.to.be.withBody;
    pm.response.to.be.json;
})
// check if response body contains a string
pm.test("Body mathches string", function () {
    pm.expect(pm.response.text()).to.include("string_you_want_to_search");
});
// check if response body is equal to a string
pm.test("Body is correct", function () {
    pm.expect(pm.response.text()).to.include("string_you_want_to_search");
});
// check for a json value
pm.test("Your test name", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.value).to.eql(100);
});
```

第一个测试用例，判断响应的状态码 `pm.response.to.have.status(201)` 断言响应的状态码事 201

第二个测试用例，判断响应数据 `pm.expect(pm.response.text()).to.include("")` 断言响应数据中一定会包含某个字段


