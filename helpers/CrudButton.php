<?php

namespace kozlovsv\crud\helpers;
use yii\helpers\Url;

/**
 * Вспомогательный класс для отрисовки стандартных кнопок КРУД (редактирование, удаление, отмена и т.п.)
 */
class CrudButton
{
    /**
     * Рисует кнопку Назад, меняет заголовок в зависимости от того задан параметр returnUrl или нет
     * @param string $indexTitle текст кнопки если параметр returnUrl не задан
     * @param string $backTitle текст кнопки если параметр returnUrl задан
     * @param array|string $defaultBackUrl ссылка по умолчанию
     * @param array $options HTML опции
     * @param string $iconName Имя Bootstrap3 иконки
     * @return string
     */
    public static function backButton($indexTitle = 'Список', $backTitle = 'Назад', $defaultBackUrl = 'index', $options = [], $iconName = 'arrow-left')
    {
        Html::addCssClass($options, ['btn', 'btn-default']);
        $title = ReturnUrl::isSetReturnUrl() ? $backTitle : $indexTitle;
        if ($iconName) $title = Html::icon($iconName) . ' ' . $title;
        return Html::a($title, ReturnUrl::getBackUrl($defaultBackUrl), $options);
    }

    /**
     * Кнопка отмена
     * @param string $text
     * @param array|string $defaultBackUrl - URL возврата по умолчанию
     * @return string
     */
    public static function cancelButton($text = 'Отмена', $defaultBackUrl = 'index')
    {
        return Html::a($text, ReturnUrl::getBackUrl($defaultBackUrl), ['class' => 'btn btn-default form-cancel']);
    }
    /**
     * Кнопка отмена
     * @param string $permissionCategory наименование раздела для определения разрешения
     * @param bool $isModal - вызвать модальный диалог или нет
     * @param string $text - Текст кнопки
     * @param array $url - URL кнопки
     * @param array $options - Html опции кнопки
     * @return string
     */
    public static function createButton($permissionCategory, $isModal = true, $text = 'Добавить', $url = ['create'], $options = [])
    {
        if (!ModelPermission::canCreate($permissionCategory)) return '';
        $defOptions = ['class' => 'btn btn-success btn-create', 'data-modal' => $isModal ? 1 : 0, 'data-pjax' => 0];
        $options = array_merge($defOptions, $options);
        return Html::a($text, $url, $options);
    }

    /**
     * Кнопка сохранить
     * @param string $text - Текст кнопки
     * @param array $options - Html опции кнопки
     * @return string
     */
    public static function saveButton($text = 'Сохранить', $options = ['class' => 'btn btn-primary'])
    {
        return Html::submitButton($text, $options);
    }

    /**
     * @param string $permissionCategory Категория разрешения
     * @param int $id ID записи
     * @param bool $isModal Отображать форму редактирования в модальном окне
     * @param array $url URL редактирования. Если пусто то используется URL по умолчанию
     * @return string
     */
    public static function editButton($permissionCategory, $id, $isModal, $url = [])
    {
        if (!ModelPermission::canUpdate($permissionCategory)) return '';
        if (empty($url)) $url = ['update', 'id' => $id, ReturnUrl::REQUEST_PARAM_NAME => Url::to(['view', 'id' => $id])];
        return Html::a(Html::icon('pencil'), $url,
            ['class' => 'btn btn-primary', 'data-modal' => $isModal ? 1 : 0]);
    }

    /**
     * @param string $permissionCategory Категория разрешения
     * @param int $id ID записи
     * @param array $url URL редактирования. Если пусто то используется URL по умолчанию
     * @return string
     */
    public static function deleteButton($permissionCategory, $id, $url = [])
    {
        if (!ModelPermission::canDelete($permissionCategory)) return '';
        if (empty($url)) $url = ['delete', 'id' => $id];
        return Html::a(Html::icon('trash'), $url, [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => 'Удалить запись?',
                'method' => 'post',
            ],
        ]);
    }
}
