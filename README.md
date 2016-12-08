# think-kcaptcha

thinkphp5 KCAPTCHA验证码类库

## 安装

> composer require ganlvtech/think-kcaptcha

请确保在配置文件中开启了URL路由

##使用

###模板里输出验证码

```html
<div>{:captcha_img()}</div>
```

或者

```html
<div><img src="{:captcha_src()}" alt="captcha" /></div>
```

> 上面两种的最终效果是一样的

### 控制器里验证

使用TP5的内置验证功能即可

```php
$this->validate($data,[
    'captcha|验证码'=>'require|captcha'
]);
```

或者手动验证

```php
if(!captcha_check($captcha)){
    //验证失败
};
```

### 验证码配置

在应用配置文件中可以添加下列配置参数

```php
'captcha' => [
    // 验证码加密密钥
    'seKey' => 'ThinkPHP.CN',
    // 验证码过期时间（s）
    'expire' => 1800,
    // 验证成功后是否重置
    'reset' => true,
    // 验证码字符集合
    'codeSet' => '23456789abcdegikpqsvxyz',
    // 验证码图片宽度
    'imageW' => 160,
    // 验证码图片高度
    'imageH' => 80,
    // 验证码位数
    'length' => mt_rand(5, 7),
    // 【KCAPTCHA专用】KCAPTCHA字体图片所在目录
    'fontsdir' => 'fonts',
    // 【KCAPTCHA专用】KCAPTCHA字体图片中的字符表
    'alphabet' => "0123456789abcdefghijklmnopqrstuvwxyz",
    // 【KCAPTCHA专用】波动幅度
    'fluctuation_amplitude' => 8,
    // 【KCAPTCHA专用】白色杂点密度（0表示没有）
    'white_noise_density' => 1 / 6,
    // 【KCAPTCHA专用】黑色杂点密度（0表示没有）
    'black_noise_density' => 1 / 30,
    // 【KCAPTCHA专用】字符是否强制粘连
    'no_spaces' => true,
    // 【KCAPTCHA专用】下方是否显示credits（开启会在高度方向额外增加12px）（注：credit表示对原作者及其他有贡献者的谢启、及鸣谢者姓名）
    'show_credits' => true,
    // 【KCAPTCHA专用】credits内容（如果为空，则显示HTTP_HOST）
    'credits' => 'www.captcha.ru',
    // 【KCAPTCHA专用】验证码文字颜色
    'foreground_color' => [mt_rand(0, 80), mt_rand(0, 80), mt_rand(0, 80)],
    // 【KCAPTCHA专用】验证码背景颜色
    'background_color' => [mt_rand(220, 255), mt_rand(220, 255), mt_rand(220, 255)],
    // 【KCAPTCHA专用】输出JPEG是压缩质量
    'jpeg_quality' => 75,
],
```
