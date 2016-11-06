<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

class DestroyHelper extends BaseSupport
{

    public function logic()
    {
        $destroy = 0;

        $this->execCallable();

        if (isset($this->parameters['id'])) {
            $destroy = $this->controller->model->destroy($this->parameters['id']);
        }

        if ($this->controller->request->ajax()) {
            return ['status' => $destroy];
        }

        if ($destroy) {
            toastr()->success(config("messages.delete.success", 'Ação realizada'), 'Sucesso!');
        } else {
            toastr()->error(config("messages.delete.error", 'Não consegui excluir'), 'Ação não realizada');
        }

        return redirect($this->controller->view->urlBase);
    }
}
