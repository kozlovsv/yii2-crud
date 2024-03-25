<?php

namespace kozlovsv\crud\classes;

use kozlovsv\crud\helpers\ReturnUrl;
use yii\base\BaseObject;
use yii\web\Controller;

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
     *  URL для возврата. Формат для Url::to().
     * @var string|array
     */
    public $backUrl = 'index';

    /**
     * Если = true, то к URL будет доабвяляться параметр ID
     * @var bool
     */
    public $addIdParemeter = false;

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


    public function back($id = null)
    {
        $url = $this->addIdParemeter? ReturnUrl::addIdToUrl($this->backUrl, $id) : $this->backUrl;
        return $this->hardRedirect ? $this->controller->redirect($url) : ReturnUrl::goBack($this->controller, $url);
    }
}