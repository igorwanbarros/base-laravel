<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

use Igorwanbarros\Php2HtmlLaravel\Panel\PanelViewLaravel;

class FormHelper extends BaseSupport
{

    protected static $widgetViewClass = PanelViewLaravel::class;


    public function logic()
    {
        $this->controller->resourceView = $this->controller->resourceView ?: 'base-laravel::form-default';

        if ($this->controller->view->isAjax) {
            $this->controller->resourceView .= '-ajax';
        }

        $view   = $this->controller->view;
        $model  = $this->controller->model;
        $id     = null;

        if (isset($this->parameters['id'])) {
            $id = $this->parameters['id'];
        }

        $view->model = $model->findOrNew($id);

        $this->_widgets($view, $id);

        $this->execCallable();

        return $this->controller->render($this->controller->resourceView);
    }


    public static function setWidgetViewClass($class)
    {
        static::$widgetViewClass = $class;
    }


    public static function getWidgetViewClass()
    {
        return static::$widgetViewClass;
    }


    /**
     * @param $view
     * @param $id
     */
    protected function _widgets($view, $id)
    {
        $title = sprintf($this->controller->title, $id ? 'Editar' : 'Adicionar');
        $old = $this->controller->request->old();
        $form = $this->controller->form->fill($view->model);

        if (count($old) > 0) {
            $form = $this->controller->form->fill($old);
        }

        if (!isset($view->widget)) {
            $widgetView = static::$widgetViewClass;
            $view->widget = new $widgetView($title);
            $view->widget->setBody($form);
        }

        if ($view->isAjax) {
            $view->widget = $form;
        }
    }
}
