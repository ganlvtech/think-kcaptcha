<?php
\think\Route::get('captcha/[:id]', '\think\captcha\KCaptchaController@index');
\think\Validate::extend('captcha', function ($value, $id = '') {
    return captcha_check($value, $id);
});
\think\Validate::setTypeMsg('captcha', '验证码错误!');
if (!function_exists('captcha')) {
    /**
     * 直接输出验证码图片
     *
     * @param string $id
     * @param array $config 覆盖config.php中的设置
     *
     * @return \think\Response
     */
    function captcha($id = '', $config = [])
    {
        $captcha = new \think\captcha\KCaptcha($config);
        return $captcha->entry($id);
    }
}
if (!function_exists('captcha_src')) {
    /**
     * 输出获取验证码图片的url
     *
     * @param string $id
     *
     * @return string
     */
    function captcha_src($id = '')
    {
        return \think\Url::build('\think\captcha\KCaptchaController@index', [
            'id' => $id,
        ]);
    }
}
if (!function_exists('captcha_img')) {
    /**
     * 输出包含验证码src的img标签
     *
     * @param string $id
     *
     * @return string
     */
    function captcha_img($id = '')
    {
        return '<img src="' . captcha_src($id) . '" alt="captcha" />';
    }
}
if (!function_exists('captcha_check')) {
    /**
     * 检查用户输入的验证码是否正确
     *
     * @param string $value 用户输入的验证码
     * @param string $id
     * @param array $config 覆盖config.php中的设置
     *
     * @return bool
     */
    function captcha_check($value, $id = '', $config = [])
    {
        $captcha = new \think\captcha\KCaptcha($config);
        return $captcha->check($value, $id);
    }
}