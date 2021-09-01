<?php

namespace Illusionist\Captcha;

use GuzzleHttp\Client;
use Illusionist\Captcha\Contracts\Captcha as CaptchaContract;

abstract class AbstractNetworkCaptcha implements CaptchaContract
{
    /**
     * The GuzzleHttp client implementation
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new network captcha instance
     *
     * @return void
     */
    public function __construct($baseUri)
    {
        $this->client = new Client(['base_uri' => $baseUri, 'http_errors' => true]);
    }

    /**
     * Create and send an HTTP request
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $options
     * @return string|object
     */
    protected function request($method, $uri, array $options)
    {
        $response = $this->client->request($method, $uri, $options);

        return $this->isJson($response)
            ? json_decode((string)$response->getBody())
            : (string)$response->getBody();
    }

    /**
     * Determine if the response is sending JSON
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @return boolean
     */
    protected function isJson($response)
    {
        $type = $response->getHeader('Content-Type')[0];

        return mb_strpos($type, '/json') !== false ||
            mb_strpos($type, '+json') !== false;
    }

    /**
     * Get the client IP address
     *
     * @return string
     */
    protected function getClientIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    }
}
