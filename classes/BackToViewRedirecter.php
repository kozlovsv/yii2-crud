<?php

namespace kozlovsv\crud\classes;

use kozlovsv\crud\helpers\ReturnUrl;
use yii\base\Model;

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
     * Если = true, то редирект будет осуществлен точно по $backUrl без логики возврата по ReturnUrl и пр.
     * @var bool
     */
    public $hardRedirect = true;

    /**
     * @inheritdoc
     */
    protected function getBackUrl(string|int|null $id, ?Model $model): array|string
    {
        $url = parent::getBackUrl($id, $model);
        return ReturnUrl::addIdToUrl($url, $id);
    }
}