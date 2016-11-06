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
