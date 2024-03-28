<?php

namespace kozlovsv\crud\controllers\actions;

class ActionCrudView extends BaseCrudAction
{
    use RenderIfAjaxTrait;

    /**
     * @var string
     */
    public string $viewName = 'view';

    protected function doAction($model, $id) {
        return $this->renderIfAjax($this->viewName, compact('model'));
    }
}