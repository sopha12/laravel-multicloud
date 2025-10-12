<?php

declare(strict_types=1);

namespace Subhashladumor\LaravelMulticloud;

use Illuminate\Support\ServiceProvider;
use Subhashladumor\LaravelMulticloud\Commands\CloudDeployCommand;
use Subhashladumor\LaravelMulticloud\Commands\CloudUsageCommand;

/**
 * Laravel MultiCloud Service Provider
 * 
 * Registers the package services and publishes configuration files
 * 
 * @package Subhashladumor\LaravelMulticloud
 * @author Subhash Ladumor <subhashladumor@gmail.com>
 * @license MIT
 */
class LaravelMulticloudServiceProvider extends ServiceProvider
{
    /**
     * Register services
     * 
     * @return void
     */
    public function register(): void
    {
        // Register the CloudManager as a singleton
        $this->app->singleton('multicloud', function ($app) {
            return new CloudManager($app);
        });

        // Register the CloudManager with its interface
        $this->app->singleton(CloudManager::class, function ($app) {
            return $app['multicloud'];
        });

        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/Config/multicloud.php',
            'multicloud'
        );
    }

    /**
     * Bootstrap services
     * 
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/multicloud.php' => config_path('multicloud.php'),
            ], 'multicloud-config');

            // Register Artisan commands
            $this->commands([
                CloudDeployCommand::class,
                CloudUsageCommand::class,
            ]);
        }

        // Load package routes if needed
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    /**
     * Get the services provided by the provider
     * 
     * @return array
     */
    public function provides(): array
    {
        return [
            'multicloud',
            CloudManager::class,
        ];
    }
}
