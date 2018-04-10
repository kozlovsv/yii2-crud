<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 05.10.2017
 * Time: 19:15
 */

namespace kozlovsv\crud\widgets;


use kartik\builder\Form;
use kozlovsv\crud\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Расширение построителя форм kartik\builder\Form
 */
class FormBuilder extends Form
{
    /**
     * @var array the basic inputs
     */
    protected static $_textInputs = [
        self::INPUT_TEXT => true,
        self::INPUT_PASSWORD => true,
        self::INPUT_TEXTAREA => true,
    ];

    /**
     * Prepares attributes based on visibility setting
     *
     * @param array $attributes the attributes to be prepared
     */
    protected static function prepareAttributes(&$attributes = [])
    {
        $newAttributes = [];
        foreach ($attributes as $key => $setting) {
            if (is_string($setting)) {
                list($newKey, $newSetting) = self::prepareAttribute($setting);
                $newAttributes[$newKey] = $newSetting;
            } else {
                $newAttributes[$key] = $setting;
            }
        }

        $attributes = $newAttributes;
        parent::prepareAttributes($attributes);

        //Автофокус  + максимальная длинна
        $first = true;
        foreach ($attributes as $key => &$setting) {
            $opt = [];
            if ($first) {
                $opt['autofocus'] = true;
            }
            $first = false;
            if (!empty(static::$_textInputs[ArrayHelper::getValue($setting, 'type')])) {
                $opt['maxlength'] = true;
            }

            if (!empty($opt)) {
                $setting = array_merge(['options' => $opt], $setting);
            }
        }
    }

    protected static function prepareAttribute($setting)
    {
        $arr = explode(':', $setting);
        $attribute = $arr[0];
        $setting = ['type' => self::INPUT_TEXT];
        if (!empty($arr[1])) {
            if ($arr[1] == 'fa') {
                $iconName = !empty($arr[2]) ? $arr[2] : 'file-alt';
                $setting['prepend'] = Html::fa($iconName);
            } elseif ($arr[1] == 'gi') {
                $iconName = !empty($arr[2]) ? $arr[2] : 'file-alt';
                $setting['prepend'] = Html::icon($iconName);
            }
        }

        return [$attribute, $setting];
    }
}