<?php

namespace Igorwanbarros\BaseLaravel\Http\Support;

class IndexPanelAjaxHelper extends IndexHelper
{
    protected function _setWidgetFromView()
    {
        $widgetView = $this->controller->form . $this->controller->view->table;
        $this->controller->view->widget = $widgetView;
    }
}