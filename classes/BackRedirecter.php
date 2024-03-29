<?php

namespace kozlovsv\crud\classes;

use kozlovsv\crud\helpers\ReturnUrl;
use yii\base\BaseObject;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;

/**
 * Класс возврата после совершения CRUD операции
 */
class BackRedirecter extends BaseObject implements IBackRedirecrer
{
    /**
     * @var Controller
     */
    protected Controller $controller;

    /**
     *  URL для возврата.
     *  Формат для Url::to().
     *  Может быть call_back функцией function (string|int|null $id, ?Model $model): array|string
     * @var string|array|callable
     */
    public $backUrl = 'index';

    /**
     * Если = true, то редирект будет осуществлен точно по $backUrl без логики возврата по ReturnUrl и пр.
     * @var bool
     */
    public $hardRedirect = false;

    public function __construct(Controller $controller, $config = [])
    {
        $this->controller = $controller;
        parent::__construct($config);
    }

    /**
     * Redirects the user back to the previous page or a specified URL.
     *
     * @param string|int|null $id The ID value.
     * @param Model|null $model The model object or model class name.
     * @return string|Response The URL to redirect to, or an instance of `yii\web\Response`.
     */
    public function back(string|int|null $id = null, ?Model $model = null): string|Response
    {
        $url = $this->getBackUrl($id, $model);
        return $this->hardRedirect ? $this->controller->redirect($url) : ReturnUrl::goBack($this->controller, $url);
    }

    /**
     * Retrieves the URL to redirect the user back to the previous page or a specified URL.
     *
     * @param string|int|null $id The ID value.
     * @param Model|null $model The model object or model class name.
     * @return array|string The URL to redirect to as an array or string.
     */
    protected function getBackUrl(string|int|null $id, ?Model $model): array|string
    {
        if (is_callable($this->backUrl)) return call_user_func($this->backUrl, $id, $model);
        return $this->backUrl;
    }
}