<?php

namespace Igorwanbarros\BaseLaravel\Providers;

use Illuminate\Support\ServiceProvider;

class BaseLaravelServiceProvider extends ServiceProvider
{

    protected $commands = [
        '\Laravelista\LumenVendorPublish\VendorPublishCommand'
    ];


    /**
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
        $this->app->register(ToastrServiceProvider::class);
        $this->app->configure('toastr');
    }


    /**
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../Migrations' => base_path('database/migrations/'),
            __DIR__ . '/../assets' => base_path('public/assets/base-laravel/'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/../Resources/view', 'base-laravel');

        require_once(__DIR__ . '/../Http/routes.php');

        require_once(__DIR__ . '/../helpers/helper.php');

        if (file_exists(base_path('config/messages.php'))) {
            $this->app->configure('messages');
        }
    }
}
