<?php

namespace Sendzap\Laravel\Tests;

use Sendzap\Laravel\Contracts\SendzapClientContract;
use Sendzap\Laravel\Facades\Sendzap;
use Sendzap\Laravel\SendzapClient;

class SendzapServiceProviderTest extends TestCase
{
    public function test_it_binds_the_sendzap_client_singleton(): void
    {
        $client = $this->app->make('sendzap');

        $this->assertInstanceOf(SendzapClient::class, $client);
        $this->assertSame($client, $this->app->make('sendzap'));
    }

    public function test_it_binds_the_contract_to_the_same_singleton(): void
    {
        $this->assertSame(
            $this->app->make('sendzap'),
            $this->app->make(SendzapClientContract::class)
        );
    }

    public function test_the_config_is_merged_from_the_package(): void
    {
        $this->assertSame('test-key', config('sendzap.api_key'));
        $this->assertSame('https://api.example.com', config('sendzap.base_url'));
    }

    public function test_the_facade_resolves_to_the_same_singleton(): void
    {
        $this->assertSame($this->app->make('sendzap'), Sendzap::getFacadeRoot());
    }
}
