<?php

namespace Igorwanbarros\BaseLaravel\Providers;

use Illuminate\Support\ServiceProvider;
use Igorwanbarros\Php2Html\Menu\ItemMenu;
use Igorwanbarros\BaseLaravel\Widgets\Assets;
use Igorwanbarros\BaseLaravel\Widgets\AclsManager;
use Igorwanbarros\BaseLaravel\Widgets\TabsManager;
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

        $this->_registerSingletons();

        $this->_registerMenu();
    }


    /**
     * @return void
     */
    public function boot()
    {
        require_once(__DIR__ . '/../helpers/helper.php');

        $this->publishes([
            __DIR__ . '/../Migrations' => base_path('database/migrations/'),
            __DIR__ . '/../assets' => base_path('public/assets/base-laravel/'),
        ]);

        $this->_bootViews();

        if (file_exists(base_path('config/messages.php'))) {
            $this->app->configure('messages');
        }
    }


    protected function _registerSingletons()
    {
        $this->app->singleton('tabs', function ($app) {
            return new TabsManager();
        });

        $this->app->singleton('menu', function ($app) {
            return new MenuViewLaravel();
        });

        $this->app->singleton('assets', function ($app) {
            return new Assets();
        });

        $this->app->singleton('acls', function ($app) {
            return new AclsManager();
        });
    }


    protected function _bootViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/view', 'base-laravel');
        $this->app->group([
                'namespace' => 'Igorwanbarros\BaseLaravel\Http\Controllers'
            ],
            function ($app) {
                require __DIR__ . '/../Http/routes.php';
            }
        );
    }


    protected function _registerMenu()
    {
        require_once __DIR__ . '/../config/menu.php';
    }
}
