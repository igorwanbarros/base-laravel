<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

use Laravel\Lumen\Routing\Controller;

abstract class BaseSupport
{
    protected $controller;

    protected $callback;

    protected $parameters;


    public function __construct(Controller $controller, $parameters = null, callable $callback = null)
    {
        $this->controller   = $controller;
        $this->parameters   = $parameters;
        $this->callback     = $callback;
    }


    public static function support(Controller $controller, $parameters = null, callable $callback = null)
    {
        $static = new static($controller, $parameters, $callback);

        return $static->logic();
    }


    protected function execCallable()
    {
        $callback = $this->callback;

        if ($callback != null)
            $callback($this->controller);
    }


    public abstract function logic();


    public function getParameters()
    {
        return $this->parameters;
    }


    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }
}
