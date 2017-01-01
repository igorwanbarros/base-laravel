<?php
namespace Igorwanbarros\BaseLaravel\Providers;

use Igorwanbarros\BaseLaravel\Toastr\Toastr;
use Illuminate\Support\ServiceProvider;

class ToastrServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/toastr.php' => base_path('config/toastr.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('toastr', function ($app) {
            return new Toastr($app->session, $app->config);
        });
    }

    /**
     * Get the services provider by the provider
     *
     * @return array
     */
    public function provides()
    {
        return ['toastr'];
    }
}
