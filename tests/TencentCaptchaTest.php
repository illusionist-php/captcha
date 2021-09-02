<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illusionist\Captcha\Tencent\Captcha;
use Mockery;

class TencentCaptchaTest extends TestCase
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
        $data = $this->captcha->getData();

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('data', $data);
    }

    public function testVerify()
    {
        $this->assertFalse($this->captcha->verify('code'));

        $this->client->shouldReceive('request')->once()->andReturn(
            new Response(200, ['Content-Type' => 'text/json'], '{"response": 1}')
        );

        $this->assertTrue($this->captcha->verify(['Ticket' => 'Ticket', 'Randstr' => 'Randstr']));
    }
}
