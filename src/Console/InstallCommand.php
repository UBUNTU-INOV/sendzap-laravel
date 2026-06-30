<?php

namespace Sendzap\Laravel\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sendzap:install';

    /**
     * The console command description.
     */
    protected $description = 'Install and configure the SendZap Laravel SDK';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Print the beautiful ASCII art in green
        $this->line('<fg=green>  ____                  ______             </>');
        $this->line('<fg=green> / ___|  ___ _ __   __| |__  / __ _ _ __   </>');
        $this->line('<fg=green> \___ \ / _ \ \'_ \ / _` | / / / _` | \'_ \  </>');
        $this->line('<fg=green>  ___) |  __/ | | | (_| |/ /_| (_| | |_) | </>');
        $this->line('<fg=green> |____/ \___|_| |_|\__,_/_____\__,_| .__/  </>');
        $this->line('<fg=green>                                   |_|     </>');
        $this->line('');

        $this->info('Installing SendZap Laravel SDK...');

        // Publish config file
        $this->call('vendor:publish', [
            '--provider' => 'Sendzap\Laravel\SendzapServiceProvider',
            '--tag' => 'sendzap-config',
        ]);

        $this->line('');
        $this->comment('Adding SendZap environment variables to your .env file...');

        // Check if SENDZAP_API_KEY already exists in .env
        if (file_exists(base_path('.env'))) {
            $envContent = file_get_contents(base_path('.env'));

            if (! str_contains($envContent, 'SENDZAP_API_KEY')) {
                file_put_contents(base_path('.env'), PHP_EOL.'SENDZAP_API_KEY='.PHP_EOL.'SENDZAP_BASE_URL=https://api.sendzap.click/api/v1'.PHP_EOL.'SENDZAP_DEFAULT_INSTANCE_ID='.PHP_EOL, FILE_APPEND);
                $this->info('Environment variables successfully added to .env!');
            } else {
                $this->warn('SENDZAP_API_KEY already exists in your .env file.');
            }
        }

        $this->line('');
        $this->info('SendZap Laravel SDK is now installed and configured! 🚀');
        $this->line('Get your API key at: <href=https://sendzap.click>https://sendzap.click</>');
    }
}
