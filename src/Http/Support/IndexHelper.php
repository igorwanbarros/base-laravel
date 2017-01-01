<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Igorwanbarros\Php2HtmlLaravel\Form\FormViewLaravel;
use Igorwanbarros\Php2HtmlLaravel\Panel\PanelViewLaravel;
use Igorwanbarros\Php2HtmlLaravel\Table\TableViewLaravel;

class IndexHelper extends BaseSupport
{
    protected static $widgetViewClass = PanelViewLaravel::class;


    public function logic()
    {
        $this->controller->resourceView = 'base-laravel::index-default';

        if ($this->controller->view->isAjax) {
            $this->controller->resourceView = 'base-laravel::index-default-ajax';
        }

        $this->_widgets();
        $this->_setFilter();
        $this->_setTable();

        $this->_setWidgetFromView();
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


    protected function _widgets()
    {
        $model = $this->controller->model;
        $widgetView = static::$widgetViewClass;

        $this->controller->view->model = $model;
        $this->controller->view->widget = new $widgetView(sprintf($this->controller->title, ''));

        if ($this->controller->form instanceof FormViewLaravel) {
            $this->controller->form
                ->search()
                ->fill($this->controller->request->all());
        }
    }


    protected function _setFilter()
    {
        $model = $this->controller->view->model;

        if (method_exists($model, 'getDeletedAtColumn')) {
            $model = $model->whereNull($model->getDeletedAtColumn());
        }

        if (!$this->controller->request->all()) {
            return;
        }

        $fields = $this->controller->request->all();
        $columns = [];

        if ($model instanceof Builder) {
            $columns = $model->getModel()->getFillable();
        }

        if ($model instanceof Model) {
            $columns = $this->controller->model->getFillable();
        }

        foreach ($fields as $field => $value) {
            if ($value == '' || array_search($field, $columns) === false) {
                continue;
            }
            $model = $model->where($field, 'like', "%{$value}%");
        }

        $this->controller->view->model = $model;
    }


    protected function _setTable()
    {
        if (isset($this->controller->view->table) || !$this->controller->view->model) {
            return;
        }

        $this->controller->view->table = TableViewLaravel::source(
            $this->controller->headers ?: ['id' => 'Código'],
            $this->controller->view->model
        );

        $this->controller->view->table
            ->addHeader('actions', '')
            ->callback($this->_calbackTable())
            ->setLineLink(url($this->controller->view->urlBase . '/editar/%s'));
    }


    protected function _calbackTable()
    {
        return function ($row) {
            $data = $row->getData();
            $data->actions = '<a href="' . url($this->controller->view->urlBase . '/excluir/' . $data->id) . '" ' .
                'class="btn btn-xs btn-danger" ' .
                'title="Excluir">' .
                '<i class="fa fa-trash fa-fw"></i>' .
                '<span class="hidden-xs">Excluir</span>' .
                '</a>';
        };
    }


    protected function _setWidgetFromView()
    {
        $widgetBody = $this->controller->form . $this->controller->view->table;
        $this->controller->view->widget->setBody($widgetBody);
    }
}
