<?php

namespace Illusionist\Captcha\Contracts;

interface Captcha
{
    /**
     * Get the data for the captcha
     *
     * @return array
     */
    public function getData();

    /**
     * Verify the given code is valid
     *
     * @param  mixed  $code
     * @return boolean
     */
    public function verify($code);
}
