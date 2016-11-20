<?php

namespace Igorwanbarros\BaseLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Igorwanbarros\Php2Html\Menu\ItemMenu;
use Igorwanbarros\Php2HtmlLaravel\Menu\MenuViewLaravel;

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

        $this->app['menu'] = $this->app->share(function ($app) {
            return new MenuViewLaravel();
        });

        app('menu')->addItemMenu(
            'inicio',
            new ItemMenu(
                'Inicio',
                url(),
                'fa fa-home'
            )
        );
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
