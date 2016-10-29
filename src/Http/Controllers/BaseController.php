<?php

namespace Igorwanbarros\BaseLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

abstract class BaseController extends Controller
{
    public $headers;

    public $form;

    public $model;

    public $view;

    public $request;

    public $title;

    public $controllerName;

    public $userColumn;


    public function __construct(Request $request, $model = null)
    {
        $this->request  = $request;
        $this->model    = $model;
        $this->view     = new \stdClass();

        $this->view->isAjax = $this->request->ajax();

        $pathInfo = $request->getPathInfo();
        $method   = $request->getMethod();

        if (!isset($this->view->urlBase)) {
            $this->_getUrlBase();
        }

        if (!isset($this->title) || $this->title == null) {
            $this->title = "%s {$this->controllerName}";
        }

        $this->view->currentRoute   = $method.$pathInfo;
        $this->view->btnAddAjax     = false;
    }


    public function render($view, array $array = [])
    {
        if (!isset($this->view->title)) {
            $this->view->title = $this->title;
        }

        $params = (array) $this->view;

        if (count($array) > 0) {
            $params = array_merge($params, $array);
        }

        return view($view)->with($params);
    }


    private function _getUrlBase()
    {
        $className = $this->_getControllerName();

        $this->view->urlBase = strtolower($className);
    }


    private function _getControllerName()
    {
        if ($this->controllerName == null) {
            $className = get_called_class();
            $className = substr($className, strrpos($className, '\\') + 1);
            $className = str_replace('Controller', '', $className);

            $this->controllerName = $className;
        }

        return $this->controllerName;
    }
}
