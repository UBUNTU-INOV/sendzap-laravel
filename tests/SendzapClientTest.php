<?php

namespace Sendzap\Laravel\Tests;

use PHPUnit\Framework\TestCase;
use Sendzap\Laravel\SendzapClient;
use Sendzap\Laravel\Exceptions\MissingInstanceIdException;

class SendzapClientTest extends TestCase
{
    public function testMissingInstanceIdThrows()
    {
        $this->expectException(MissingInstanceIdException::class);

        $client = new SendzapClient('dummy-key', 'https://api.example.com');
        $client->sendText('123', 'hello');
    }
}
