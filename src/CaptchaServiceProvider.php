<?php

namespace Illusionist\Captcha;

use Illuminate\Support\ServiceProvider;
use Illusionist\Captcha\Geetest\Captcha as Geetest;
use Illusionist\Captcha\Tencent\Captcha as Tencent;
use Laravel\Lumen\Application as LumenApplication;

class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the application services
     *
     * @return void
     */
    public function boot()
    {
        $this->registerValidator();

        if (! $this->isLumen() && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/captcha.php' => config_path('captcha.php'),
            ]);
        }
    }

    /**
     * Register the captcha validator extension
     *
     * @return void
     */
    protected function registerValidator()
    {
        $this->app->resolving('validator', function ($validator, $app) {
            $validator->extend('captcha', function ($attribute, $value, $parameters) use ($app) {
                return $app->make(CaptchaManager::class)
                    ->driver(isset($parameters[0]) ? $parameters[0] : null)
                    ->verify($value, isset($parameters[1]) ? $parameters[1] : true);
            });
        });
    }

    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/captcha.php', 'captcha');

        if ($this->isLumen()) {
            $this->app->configure('image');
        }

        $this->registerManager();

        $this->registerDriver();
    }

    /**
     * Register the captcha manager instance.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton(CaptchaManager::class, function ($app) {
            return new CaptchaManager($app);
        });
    }

    /**
     * Register the captcha driver instance.
     *
     * @return void
     */
    protected function registerDriver()
    {
        $this->app->singleton(Tencent::class, function ($app) {

            $config = $app->make('config')->get('captcha.drivers.tencent');

            return new Tencent($config['id'], $config['key']);
        });

        $this->app->singleton(Geetest::class, function ($app) {

            $config = $app->make('config')->get('captcha.drivers.geetest');

            return new Geetest($config['id'], $config['key']);
        });
    }

    /**
     * Determine if the application framework is Lumen
     *
     * @return boolean
     */
    protected function isLumen()
    {
        return $this->app instanceof LumenApplication;
    }

    /**
     * Get the services provided by the provider
     *
     * @return array
     */
    public function provides()
    {
        return [CaptchaManager::class];
    }
}
