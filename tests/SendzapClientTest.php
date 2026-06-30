<?php

namespace Sendzap\Laravel\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Sendzap\Laravel\Exceptions\ApiException;
use Sendzap\Laravel\Exceptions\MissingInstanceIdException;
use Sendzap\Laravel\SendzapClient;

class SendzapClientTest extends BaseTestCase
{
    private function makeClient(array $responses, ?string $defaultInstanceId = 'instance-123'): SendzapClient
    {
        $handler = new MockHandler($responses);
        $httpClient = new Client(['handler' => HandlerStack::create($handler)]);

        return new SendzapClient('dummy-key', 'https://api.example.com', $defaultInstanceId, $httpClient);
    }

    public function test_missing_instance_id_throws(): void
    {
        $this->expectException(MissingInstanceIdException::class);

        $client = $this->makeClient([], null);
        $client->sendText('123', 'hello');
    }

    public function test_send_text_returns_decoded_json(): void
    {
        $client = $this->makeClient([
            new Response(200, [], json_encode(['status' => 'sent'])),
        ]);

        $result = $client->sendText('123', 'hello');

        $this->assertSame(['status' => 'sent'], $result);
    }

    public function test_set_instance_id_overrides_the_default_for_subsequent_calls(): void
    {
        $client = $this->makeClient([
            new Response(200, [], json_encode(['status' => 'sent'])),
        ], null);

        $result = $client->setInstanceId('instance-456')->sendText('123', 'hello');

        $this->assertSame(['status' => 'sent'], $result);
    }

    public function test_api_error_response_is_wrapped_in_api_exception(): void
    {
        $client = $this->makeClient([
            new Response(422, [], json_encode(['message' => 'Invalid phone number'])),
        ]);

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Invalid phone number');

        $client->sendText('123', 'hello');
    }

    public function test_list_instances_returns_decoded_json(): void
    {
        $client = $this->makeClient([
            new Response(200, [], json_encode(['data' => []])),
        ]);

        $this->assertSame(['data' => []], $client->listInstances());
    }
}
