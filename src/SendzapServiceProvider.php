<?php

namespace Sendzap\Laravel;

use Illuminate\Support\ServiceProvider;
use Sendzap\Laravel\Console\InstallCommand;
use Sendzap\Laravel\Contracts\SendzapClientContract;

class SendzapServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(__DIR__.'/../config/sendzap.php', 'sendzap');

        // Bind the client (string alias for facade) and the contract for DI
        $this->app->singleton('sendzap', function ($app) {
            $config = $app['config']['sendzap'];

            return new SendzapClient(
                $config['api_key'] ?? '',
                $config['base_url'] ?? 'https://api.sendzap.click/api/v1',
                $config['default_instance_id'] ?? null
            );
        });

        $this->app->singleton(SendzapClientContract::class, function ($app) {
            return $app->make('sendzap');
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        // Publish configuration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sendzap.php' => config_path('sendzap.php'),
            ], 'sendzap-config');

            // Register commands
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
