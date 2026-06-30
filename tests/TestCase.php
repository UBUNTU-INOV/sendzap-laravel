<?php

namespace Sendzap\Laravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sendzap\Laravel\SendzapServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SendzapServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('sendzap.api_key', 'test-key');
        $app['config']->set('sendzap.base_url', 'https://api.example.com');
        $app['config']->set('sendzap.default_instance_id', 'instance-123');
    }
}
