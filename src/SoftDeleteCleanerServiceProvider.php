<?php
namespace FalconSW\SoftDeleteCleaner;

use Illuminate\Support\ServiceProvider;

class SoftDeleteCleanerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register soft-delete-cleaner artisan command
        $this->commands([
            SoftDeleteCleanerCommand::class,
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/soft-delete-cleaner.php', 'soft-delete-cleaner');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish config file
        $configPath = __DIR__.'/../config/soft-delete-cleaner.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('soft-delete-cleaner.php');
        } else {
            $publishPath = base_path('config/soft-delete-cleaner.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }
}
