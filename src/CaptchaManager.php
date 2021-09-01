<?php

namespace Illusionist\Captcha;

use Illuminate\Support\Manager;
use Illusionist\Captcha\Contracts\Factory as FactoryContract;

class CaptchaManager extends Manager implements FactoryContract
{
    /**
     * The container instance
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

   /**
     * The configuration repository instance
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

   /**
     * Create a new captcha manager instance
     *
     * @param  \Illuminate\Contracts\Container\Container $container
     * @return void
     */
    public function __construct($container)
    {
        parent::__construct($container);

        $this->container = $container;
        $this->config = $container->make('config');
    }

    /**
     * Get the default channel driver name
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config->get('captcha.default', 'image');
    }

    /**
     * Create an instance of the image driver
     *
     * @return \Illusionist\Captcha\Image\Captcha
     */
    protected function createImageDriver()
    {
        return $this->container->make(Image\Captcha::class);
    }

    /**
     * Create an instance of the gif driver
     *
     * @return \Illusionist\Captcha\Gif\Captcha
     */
    protected function createGifDriver()
    {
        return $this->container->make(Gif\Captcha::class);
    }

    /**
     * Create an instance of the tencent driver
     *
     * @return \Illusionist\Captcha\Tencent\Captcha
     */
    protected function createTencentDriver()
    {
        return $this->container->make(Tencent\Captcha::class);
    }

    /**
     * Create an instance of the geetest driver
     *
     * @return \Illusionist\Captcha\Geetest\Captcha
     */
    protected function createGeetestDriver()
    {
        return $this->container->make(Geetest\Captcha::class);
    }
}
