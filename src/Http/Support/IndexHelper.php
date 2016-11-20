<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

use Igorwanbarros\Php2HtmlLaravel\Panel\PanelViewLaravel;
use Igorwanbarros\Php2HtmlLaravel\Table\TableViewLaravel;

class IndexHelper extends BaseSupport
{
    public function logic()
    {
        $this->controller->resourceView = 'base-laravel::index-default';

        if ($this->controller->view->isAjax) {
            $this->controller->resourceView = 'base-laravel::index-default-ajax';
        }

        $model  = $this->controller->model;

        $this->controller->view->model = $model;
        $this->controller->view->widget = new PanelViewLaravel(sprintf($this->controller->title, ''));

        if (is_object($this->controller->form)) {
            $this->controller->form = $this->controller->form->search()
                ->fill($this->controller->request->all());
        }

        $this->_setFilter();
        $this->_setTable();

        $widgetBody = $this->controller->form . $this->controller->view->table;
        $this->controller->view->widget->setBody($widgetBody);

        $this->execCallable();

        return $this->controller->render($this->controller->resourceView);
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

        foreach ($fields as $field => $value) {
            if ($value == '' || array_search($field, $this->controller->model->getFillable()) === false) {
                continue;
            }
            $model = $model->where($field, 'like', "%{$value}%");
        }

        $this->controller->view->model = $model;
    }


    protected function _setTable()
    {
        if (isset($this->controller->view->table)) {
            return;
        }

        if (!$this->controller->view->model) {
            $this->controller->view->table = '';
            return;
        }

        $this->controller->view->table = TableViewLaravel::source(
            $this->controller->headers ?: ['id' => 'Código'],
            $this->controller->view->model
        );

        $this->controller->view->table->addHeader('actions', '')
            ->callback(function ($row) {
                $data = $row->getData();
                $data->actions = '<a href="' . url($this->controller->view->urlBase . '/excluir/' . $data->id) . '" ' .
                    'class="btn btn-xs btn-danger" ' .
                    'title="Excluir">' .
                        '<i class="fa fa-trash fa-fw"></i>' .
                        '<span class="hidden-xs">Excluir</span>' .
                    '</a>';
            })
            ->setLineLink(url($this->controller->view->urlBase . '/editar/%s'));
    }
}
