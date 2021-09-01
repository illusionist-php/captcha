<?php

namespace Illusionist\Captcha\Geetest;

use Illusionist\Captcha\AbstractNetworkCaptcha;

class Captcha extends AbstractNetworkCaptcha
{
    const GT_SDK_VERSION = 'php_3.0.0';

    /**
     * The app id provided by Geetest
     *
     * @var string
     */
    protected $appId;

    /**
     * The key provided by Geetest
     */
    protected $key;

    /**
     * Create a new geetest captcha instance
     *
     * @param  string  $appId
     * @param  string  $key
     * @return void
     */
    public function __construct($appId, $key)
    {
        parent::__construct('https://api.geetest.com');

        $this->appId = $appId;
        $this->key = $key;
    }

    /**
     * Get the data for the captcha
     *
     * @return array
     */
    public function getData()
    {
        $challenge = $this->request('GET', 'register.php', [
            'query' => [
                'gt' => $this->appId,
                'ip_address' => $this->getClientIp(),
                'new_captcha' => 1,
                'sdk' => static::GT_SDK_VERSION,
            ]
        ]);

        if (strlen($challenge) != 32) {
            return false;
        }

        return [
            'type' => 'geetest',
            'data' => [
                'appid' => $this->appId,
                'challenge' => md5($challenge.$this->key),
            ],
        ];
    }

    /**
     * Verify the given code is valid
     *
     * @param  mixed  $code
     * @return boolean
     */
    public function verify($code)
    {
        if (! $this->isCode($code)) {
            return false;
        }

        $seccode = $this->request('POST', 'validate',[
            'form_params' => array_merge($code, [
                'captchaid' => $this->appId,
                'ip_address' => $this->getClientIp(),
                'sdk' => static::GT_SDK_VERSION,
            ]),
        ]);

        return $seccode == md5($code['seccode']);
    }

    /**
     * Determine if the given code is valid
     *
     * @param  mixed  $code
     * @return boolean
     */
    protected function isCode($code)
    {
        if (! is_array($code) ||
            ! isset($code['challenge'], $code['validate'], $code['seccode'])
        ) {
            return false;
        }

        return md5($this->key.'geetest'.$code['challenge']) == $code['validate'];
    }
}
