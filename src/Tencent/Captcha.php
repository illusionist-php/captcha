<?php

namespace Illusionist\Captcha\Tencent;

use Illusionist\Captcha\AbstractNetworkCaptcha;

class Captcha extends AbstractNetworkCaptcha
{
    /**
     * The app id provided by Tencent
     *
     * @var string
     */
    protected $appId;

    /**
     * The app secret key provided by Tencent
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Create a new tencent captcha instance
     *
     * @return void
     */
    public function __construct($appId, $secretKey)
    {
        parent::__construct('https://ssl.captcha.qq.com');

        $this->appId = $appId;
        $this->secretKey = $secretKey;
    }

    /**
     * Get the data for the captcha
     *
     * @return array
     */
    public function getData()
    {
        return [
            'type' => 'tencent',
            'data' => [
                'appid' => $this->appId,
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

        $res = $this->request('GET', 'ticket/verify', [
            'form_params' => array_merge($code, [
                'aid' => $this->appId,
                'AppSecretKey' => $this->secretKey,
                'UserIP' => $this->getClientIp(),
            ]),
        ]);

        return isset($res->response) && (int)$res->response === 1;
    }

    /**
     * Determine if the given code is val
     *
     * @param  mixed  $code
     * @return boolean
     */
    protected function isCode($code)
    {
        return is_array($code) &&
            isset($code['Ticket'], $code['Randstr']);
    }
}
