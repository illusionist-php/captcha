<?php

namespace Illusionist\Captcha\Contracts;

interface Factory
{
    /**
     * Get a captcha instance by name
     *
     * @param  string|null  $name
     * @return \Illusionist\Captcha\Contracts\Captcha
     */
    public function driver($name = null);
}
