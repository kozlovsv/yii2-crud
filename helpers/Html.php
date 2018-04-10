<?php

namespace kozlovsv\crud\helpers;


use yii\helpers\ArrayHelper;

class Html extends \yii\bootstrap\Html
{

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
