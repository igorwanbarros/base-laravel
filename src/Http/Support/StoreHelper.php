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

        if ($this->controller->view->isAjax) {
            return ['status' => isset($model->id) ? true : false, 'model' => $model];
        }

        $this->_setMessage($model);

        return redirect($this->controller->view->urlBase);
    }


    /**
     * @param $model
     */
    protected function _setMessage($model)
    {
        $typeMessage = $this->controller->request->get('id') ? 'update' : 'create';

        if (isset($model->id)) {
            toastr()->success(config("messages.{$typeMessage}.success", 'Ação realizada'), 'Sucesso!');
            return;
        }

        toastr()->error(config("messages.{$typeMessage}.success", 'Não consegui concluir esta ação'), 'Ação não concluída');
    }
}
