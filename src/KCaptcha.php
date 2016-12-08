<?php
namespace think\captcha;

use think\Response;
use think\Session;

class KCaptcha
{
    protected $config = [
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
        'length' => 5,
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
        'foreground_color' => [0, 0, 0],
        // 【KCAPTCHA专用】验证码背景颜色
        'background_color' => [255, 255, 255],
        // 【KCAPTCHA专用】输出JPEG是压缩质量
        'jpeg_quality' => 75,
    ];
    protected $captcha = null;

    /**
     * 构造方法 设置参数
     *
     * @access public
     *
     * @param array $config 配置参数
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->config['allowed_symbols'] = $this->config['codeSet'];
        $this->config['width'] = $this->config['imageW'];
        $this->config['height'] = $this->config['imageH'];
        $this->captcha = new \KCAPTCHA\KCAPTCHA($this->config);
    }

    /**
     * 使用 $this->name 获取配置
     *
     * @access public
     *
     * @param  string $name 配置名称
     *
     * @return mixed 配置值
     */
    public function __get($name)
    {
        return $this->config[$name];
    }

    /**
     * 设置验证码配置
     *
     * @access public
     *
     * @param  string $name 配置名称
     * @param  string $value 配置值
     *
     * @return void
     */
    public function __set($name, $value)
    {
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 检查配置
     *
     * @access public
     *
     * @param  string $name 配置名称
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    /**
     * 验证用户输入的验证码是否正确
     *
     * @access public
     *
     * @param string $code 用户验证码
     * @param string $id 验证码标识
     *
     * @return bool 用户验证码是否正确
     */
    public function check($code, $id = '')
    {
        $key = $this->authcode($this->config['seKey']) . $id;
        // 验证码不能为空
        $seCode = Session::get($key, '');
        if (empty($code) || empty($seCode)) {
            return false;
        }
        // session 过期
        if (time() - $seCode['verify_time'] > $this->config['expire']) {
            Session::delete($key, '');
            return false;
        }
        if ($this->authcode(strtoupper($code)) == $seCode['verify_code']) {
            $this->config['reset'] && Session::delete($key, '');
            return true;
        }
        return false;
    }

    /**
     * 输出验证码图片并把验证码的值保存到 $_SESSION['captcha_keystring' . $id] 中
     *
     * @access public
     *
     * @param string $id 要生成验证码的标识
     *
     * @return \think\Response
     */
    public function entry($id = '')
    {
        // 保存验证码
        $key = $this->authcode($this->config['seKey']);
        $code = $this->authcode(strtoupper($this->captcha->getKeyString()));
        $seCode = [];
        $seCode['verify_code'] = $code; // 把校验码保存到session
        $seCode['verify_time'] = time(); // 验证码创建时间
        Session::set($key . $id, $seCode, '');
        ob_start();
        imagejpeg($this->captcha->getImageResource(), null, $this->config['jpeg_quality']);
        $content = ob_get_clean();
        return Response::create($content, '', 200, ['Content-Length' => strlen($content)])->contentType('image/jpeg');
    }

    /**
     * 使用$this->seKey加密$str
     *
     * @param string $str
     *
     * @return string
     */
    private function authcode($str)
    {
        $key = substr(md5($this->config['seKey']), 5, 8);
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }
}
