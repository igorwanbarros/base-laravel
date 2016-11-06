<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

class DestroyHelper extends BaseSupport
{

    public function logic()
    {
        $destroy = 0;

        $this->execCallable();

        //dd($this->parameters, $destroy);
        if (isset($this->parameters['id'])) {
            $destroy = $this->controller->model->destroy($this->parameters['id']);
        }

        if ($this->controller->request->ajax()) {
            return ['status' => $destroy];
        }

        return redirect($this->controller->view->urlBase);
    }
}
