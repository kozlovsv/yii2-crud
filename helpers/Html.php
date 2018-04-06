<?php

namespace kozlovsv\crud\helpers;


use yii\helpers\ArrayHelper;

class Html extends \yii\bootstrap\Html
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
    public static function backButton($indexTitle = 'Список', $backTitle = 'Назад', $defAction = 'index', $options = [], $iconName = 'arrow-left') {
        self::addCssClass($options, ['btn', 'btn-default']);
        $title = ReturnUrl::isSetReturnUrl()? $backTitle : $indexTitle;
        if ($iconName) $title = self::icon('') . ' ' . $title;
        return self::a($title , ReturnUrl::getBackUrl($defAction), $options);
    }

    /**
     * @param string $name
     * @param array $options
     * @return string
     */
    public static function fa($name, $options = [])
    {
        return parent::icon($name, ArrayHelper::merge(['prefix' => 'fa fa-'], $options));
    }

    /**
     * Рисует $label в цветной штуке
     * @param string $label
     * @param string $cssClass
     * @return string
     */
    public static function colorLabel($label, $cssClass)
    {
        if ($cssClass === null) {
            return $label;
        }

        return self::tag('span', $label, ['class' => "label label-rounded label-$cssClass"]);
    }
}
