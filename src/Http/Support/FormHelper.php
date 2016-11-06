<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

use Igorwanbarros\Php2Html\Panel\PanelView;

class FormHelper extends BaseSupport
{
    public function logic()
    {
        $this->controller->resourceView = 'base-laravel::form-default';

        $view   = $this->controller->view;
        $model  = $this->controller->model;
        $id     = null;

        if (isset($this->parameters['id'])) {
            $id = $this->parameters['id'];
        }

        $view->model = $model->findOrNew($id);

        $title = sprintf($this->controller->title, $id ? 'Editar' : 'Adicionar');
        $form  = $this->controller->form->fill($view->model);
        $old   = $this->controller->request->old();

        if (count($old) > 0) {
            $form = $this->controller->form->fill($old);
        }

        $view->widget = new PanelView($title);
        $view->widget->setBody($form);

        if ($view->isAjax) {
            $view->widget = $form;
        }

        $this->execCallable();

        return $this->controller->render($this->controller->resourceView);
    }
}
