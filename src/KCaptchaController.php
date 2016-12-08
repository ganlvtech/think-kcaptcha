<?php
namespace think\captcha;

use think\Config;

class KCaptchaController
{
    public function index($id = '')
    {
        $captcha = new KCaptcha((array)(Config::get('captcha')));
        return $captcha->entry($id);
    }
}