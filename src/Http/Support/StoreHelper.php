<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

class StoreHelper extends BaseSupport
{

    public function logic()
    {
        $this->controller->view->customAttributes = $this->controller->request->all();

        $this->execCallable();

        $this->controller->validate(
            $this->controller->request,
            $this->controller->form->getRules(),
            isset($this->controller->view->messages)
                ? $this->controller->view->messages
                : [],
            $this->controller->view->customAttributes
        );

        $model = $this->controller->model
            ->saveOrUpdate($this->controller->view->customAttributes);


        return $this->controller->view->isAjax
            ? ['status' => isset($model->id) ? true : false, 'model' => $model]
            : redirect($this->controller->view->urlBase);
    }

}