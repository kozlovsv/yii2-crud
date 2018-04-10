<?php
namespace kozlovsv\crud\helpers;

/**
 * Вспомогательный класс для отрисовки стандартных кнопок КРУД (редактирование, удаление, отмена и т.п.)
 */
class CrudButton {
    /**
     * Рисует кнопку Назад, меняет заголовок в зависимости от того задан параметр returnUrl или нет
     * @param string $indexTitle текст кнопки если параметр returnUrl не задан
     * @param string $backTitle текст кнопки если параметр returnUrl задан
     * @param string $defAction route по умолчанию
     * @param array $options HTML опции
     * @param string $iconName Имя Bootstrap3 иконки
     * @return string
     */
    public static function backButton($indexTitle = 'Список', $backTitle = 'Назад', $defAction = 'index', $options = [], $iconName = 'arrow-left') {
        Html::addCssClass($options, ['btn', 'btn-default']);
        $title = ReturnUrl::isSetReturnUrl()? $backTitle : $indexTitle;
        if ($iconName) $title = Html::icon('') . ' ' . $title;
        return Html::a($title , ReturnUrl::getBackUrl($defAction), $options);
    }

    /**
     * Кнопка отмена
     * @return string
     */
    public static function cancelButton()
    {
        return Html::a('Отмена', ReturnUrl::getBackUrl(), ['class' => 'btn btn-default form-cancel']);
    }

    /**
     * Кнопка отмена
     * @param bool $isModal - вызвать модальный диалог или нет
     * @param string $text - Текст кнопки
     * @param array $url - URL кнопки
     * @param array $options - Html опции кнопки
     * @return string
     */
    public static function createButton($isModal = true, $text = 'Добавить', $url = ['create'], $options = [])
    {
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
}
