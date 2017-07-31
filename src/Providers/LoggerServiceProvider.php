<?php

namespace Merkeleon\Logger\Providers;

use Illuminate\Support\ServiceProvider;
use Merkeleon\Logger\Logger;

class LoggerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/logger.php' => config_path('merkeleon/logger.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/logger.php', 'merkeleon.logger'
        );

        $this->app->singleton('merkeleon.logger', function ($app) {
            $logger = new Logger(config('merkeleon.logger'));

            return $logger;
        });

        $this->app->alias('merkeleon.logger', Logger::class);
    }
}
