<?php

namespace Illusionist\Captcha\Facades;

use Illuminate\Support\Facades\Facade;
use Illusionist\Captcha\CaptchaManager;

/**
 * @method static \Illusionist\Captcha\Contracts\Captcha driver(string $name = null)
 * @method static array getData()
 * @method static boolean verify($code)
 *
 * @see \Illusionist\Captcha\CaptchaManager
 */
class Captcha extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CaptchaManager::class;
    }
}
