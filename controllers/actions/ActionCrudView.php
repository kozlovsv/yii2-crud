<?php

namespace kozlovsv\crud\controllers\actions;

abstract class ActionCrudView extends BaseCrudAction
{
    use RenderIfAjaxTrait;

    /**
     * @var string
     */
    public string $viewName = 'view';

    protected function doAction($model) {
        return $this->renderIfAjax($this->viewName, compact('model'));
    }
}