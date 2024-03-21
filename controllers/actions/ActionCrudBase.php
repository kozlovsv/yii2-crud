<?php

namespace kozlovsv\crud\controllers\actions;

use kozlovsv\crud\helpers\ReturnUrl;
use yii\base\Action;
use yii\web\Response;

class ActionCrudBase extends Action
{
    use RenderIfAjaxTrait;

    /**
     * @var string
     */
    public string $viewName = '';

    /**
     * URL для возврата назад после действия
     * @var array|string
     */
    public $backUrl = 'index';

    /**
     * URL для возврата назад если в действии произошла ошибка
     * @var array|string
     */
    public $errorBackUrl = 'index';

    /**
     * @return Response
     */
    protected function goBack()
    {
        return ReturnUrl::goBack($this->controller, $this->backUrl);
    }

    /**
     * @return Response
     */
    protected function goBackAfterError()
    {
        return ReturnUrl::goBack($this->controller, $this->errorBackUrl);
    }
}