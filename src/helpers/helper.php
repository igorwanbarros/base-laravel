<?php


if (! function_exists('toastr')) {
    /**
     * @return Igorwanbarros\BaseLaravel\Toastr\Toastr
     */
    function toastr()
    {
        return app('toastr');
    }
}

if (! function_exists('menu')) {
    /**
     * @return Igorwanbarros\Php2HtmlLaravel\Menu\MenuViewLaravel
     */
    function menu()
    {
        return app('menu');
    }
}

if (! function_exists('tabs')) {
    /**
     * @return Igorwanbarros\BaseLaravel\Widgets\TabsManager
     */
    function tabs()
    {
        return app('tabs');
    }
}

if (! function_exists('acls')) {
    /**
     * @return Igorwanbarros\BaseLaravel\Widgets\AclsManager
     */
    function acls()
    {
        return app('acls');
    }
}

if (! function_exists('assets')) {
    /**
     * @return Igorwanbarros\BaseLaravel\Widgets\Assets
     */
    function assets()
    {
        return app('assets');
    }
}

if (! function_exists('app_session')) {
    /**
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed
     */
    function app_session($key = null, $default = null)
    {
        $keySession = env('APP_KEY_SESSION', 'app.session');

        if ($key) {
            $keySession .= ".{$key}";
        }

        return session($keySession, $default);
    }
}
