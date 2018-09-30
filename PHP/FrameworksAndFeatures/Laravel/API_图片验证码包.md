## Laravel API 开发图片验证码包
### mewsbstudio/captcha
地址:
https://github.com/mewebstudio/captcha

安装 `compser require grepwar/captcha`

使用
```
use Gregwar\Captcha\CaptchaBuilder

public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.str_random(15);
        $phone = $request->phone;

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
$captcha->inline() 得到 base64 格式字符串 img