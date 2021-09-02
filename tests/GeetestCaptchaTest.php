<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illusionist\Captcha\Geetest\Captcha;
use Mockery;

class GeetestCaptchaTest extends TestCase
{
    /**
     * @var \Mockery\Mock
     */
    protected $captcha;

    /**
     * @var \Mockery\Mock
     */
    protected $client;

    public function setUp()
    {
        $this->captcha = Mockery::mock(Captcha::class, ['appId', 'key'])->makePartial();
        $this->client = Mockery::mock(Client::class);

        $this->mockProperty($this->captcha, 'client', $this->client);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetData()
    {
        $this->client->shouldReceive('request')->once()->andReturn(
            new Response(200, ['Content-Type' => 'text/javascript'], md5('challenge'))
        );

        $data = $this->captcha->getData();

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('data', $data);

        $this->client->shouldReceive('request')->once()->andReturn(
            new Response(200, ['Content-Type' => 'text/javascript'], '')
        );

        $this->assertFalse($this->captcha->getData());
    }

    public function testVerify()
    {
        $this->assertFalse($this->captcha->verify('code'));

        $this->client->shouldReceive('request')->once()->andReturn(
            new Response(200, ['Content-Type' => 'text/javascript'], md5('seccode'))
        );

        $this->assertTrue($this->captcha->verify([
            'challenge' => 'challenge',
            'validate' => md5('keygeetestchallenge'),
            'seccode' => 'seccode'
        ]));
    }
}
