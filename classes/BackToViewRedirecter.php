<?php

namespace kozlovsv\crud\classes;

use kozlovsv\crud\helpers\ReturnUrl;

/**
 * Класс возврата после совершения CRUD операции не в 'index' в на страницу просмотра 'view'
 */
class BackToViewRedirecter extends BackRedirecter
{
    /**
     *  URL для возврата. Формат для Url::to().
     * @var string|array
     */
    public $backUrl = 'view';

    /**
     * Если = true, то к URL будет доабвяляться параметр ID
     * @var bool
     */
    public $addIdParemeter = true;

    /**
     * Если = true, то редирект будет осуществлен точно по $backUrl без логики возврата по ReturnUrl и пр.
     * @var bool
     */
    public $hardRedirect = true;


    public function back($id = null)
    {
        $url = $this->addIdParemeter? ReturnUrl::addIdToUrl($this->backUrl, $id) : $this->backUrl;
        return $this->hardRedirect ? $this->controller->redirect($url) : ReturnUrl::goBack($this->controller, $url);
    }
}