<?php

namespace Tests\Utils;

use Laeo\Paysoul\Utils\HttpClient;
use Tests\TestCase;

class HttpClientTest extends TestCase
{
    protected $http;

    public function setUp()
    {
        $this->http = new HttpClient();
    }

    public function testSendRequest()
    {
        $jsonString = $this->http->request('get', 'https://httpbin.org/user-agent', [
            CURLOPT_USERAGENT => 'http client',
        ]);

        $this->assertJsonStringEqualsJsonString(
            json_encode(['user-agent' => 'http client']),
            $jsonString
        );
    }
}
