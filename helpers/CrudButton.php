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
     * @param string $defAction route по умолчанию
     * @param array $options HTML опции
     * @param string $iconName Имя Bootstrap3 иконки
     * @return string
     */
    public static function backButton($indexTitle = 'Список', $backTitle = 'Назад', $defAction = 'index', $options = [], $iconName = 'arrow-left')
    {
        Html::addCssClass($options, ['btn', 'btn-default']);
        $title = ReturnUrl::isSetReturnUrl() ? $backTitle : $indexTitle;
        if ($iconName) $title = Html::icon($iconName) . ' ' . $title;
        return Html::a($title, ReturnUrl::getBackUrl($defAction), $options);
    }

    /**
     * Кнопка отмена
     * @param string $text
     * @return string
     */
    public static function cancelButton($text = 'Отмена')
    {
        return Html::a($text, ReturnUrl::getBackUrl(), ['class' => 'btn btn-default form-cancel']);
    }

    /**
     * Кнопка отмена
     * @param string $tableName наименование раздела для определения разрешения
     * @param bool $isModal - вызвать модальный диалог или нет
     * @param string $text - Текст кнопки
     * @param array $url - URL кнопки
     * @param array $options - Html опции кнопки
     * @return string
     */
    public static function createButton($tableName, $isModal = true, $text = 'Добавить', $url = ['create'], $options = [])
    {
        if (!ModelPermission::canCreate($tableName)) return '';
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
     * @param \yii\db\ActiveRecord $model
     * @param bool $isModal
     * @return string
     */
    public static function editButton($model, $isModal)
    {
        if (!ModelPermission::canUpdate($model->tableName())) return '';
        return Html::a(Html::icon('pencil'), ['update', 'id' => $model->getPrimaryKey(), ReturnUrl::REQUEST_PARAM_NAME => Url::to(['view', 'id' => $model->getPrimaryKey()])],
            ['class' => 'btn btn-primary', 'data-modal' => $isModal ? 1 : 0]);
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @return string
     */
    public static function deleteButton($model)
    {
        if (!ModelPermission::canDelete($model->tableName())) return '';

        return Html::a(Html::icon('trash'), ['delete', 'id' => $model->getPrimaryKey()], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => 'Удалить запись?',
                'method' => 'post',
            ],
        ]);

    }
}
