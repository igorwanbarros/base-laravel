<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

class StoreHelper extends BaseSupport
{

    public function logic()
    {
        $this->controller->view->customAttributes = $this->controller->request->all();

        $this->execCallable();

        $this->_validate();

        $model = $this->_save();

        if ($this->controller->view->isAjax) {
            return [
                'status' => isset($model->id),
                'model' => $model
            ];
        }

        $this->_setMessage($model);

        return redirect($this->controller->view->urlBase);
    }


    /**
     * @param $model
     */
    protected function _setMessage($model)
    {
        $typeMessage = $this->controller->request->get('id')
            ? 'update'
            : 'create';

        if (isset($model->id)) {
            toastr()->success(
                config("messages.{$typeMessage}.success", 'Ação realizada'),
                'Sucesso!'
            );

            return;
        }

        toastr()->error(
            config("messages.{$typeMessage}.success", 'Não consegui concluir esta ação'),
            'Ação não concluída'
        );
    }


    protected function _validate()
    {
        $this->controller->validate(
            $this->controller->request,
            $this->controller->form->getRules(),
            isset($this->controller->view->messages)
                ? $this->controller->view->messages
                : [],
            $this->controller->view->customAttributes
        );
    }


    protected function _save()
    {
        if (!method_exists($this->controller->model, 'saveOrUpdate')) {
            return null;
        }

        return $this->controller->model
            ->saveOrUpdate(
                $this->controller->view->customAttributes
            );
    }
}
