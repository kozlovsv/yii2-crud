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
     * Установить автофокус по умолчанию
     * @var bool
     */
    public $needAutoFocus = true;

    /**
     * @var array the basic inputs
     */
    protected static $_textInputs = [
        self::INPUT_TEXT => true,
        self::INPUT_PASSWORD => true,
        self::INPUT_TEXTAREA => true,
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setAutoFocus();
    }

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

        //Настраиваем наиболее часто используемые функции для КРУД
        foreach ($attributes as $key => &$setting) {
            //Ограничение максимальной длинны
            if (!empty(static::$_textInputs[ArrayHelper::getValue($setting, 'type')])) {
                $setting = array_merge(['options' => ['maxlength' => true]], $setting);
            }
        }
    }

    protected static function prepareAttribute($setting)
    {
        $arr = explode(':', $setting);
        $attribute = $arr[0];
        $setting = ['type' => self::INPUT_TEXT];
        if (!empty($arr[1])) {
            $icon = '';
            if ($arr[1] == 'fa') {
                $iconName = !empty($arr[2]) ? $arr[2] : 'file-alt';
                $icon = Html::fa($iconName);
            } elseif ($arr[1] == 'gi') {
                $iconName = !empty($arr[2]) ? $arr[2] : 'file-alt';
                $icon = Html::icon($iconName);
            }
            if ($icon) {
                $setting['fieldConfig'] = ['addon' => ['prepend' => ['content' => $icon]]];
            }
        }
        return [$attribute, $setting];
    }

    /**
     * Устанавливаем флаг автофокуса для первого элемента массива
     */
    protected function setAutoFocus()
    {
        if (!$this->needAutoFocus || empty($this->attributes)) return;
        reset($this->attributes);
        $key = key($this->attributes);
        $value = current($this->attributes);
        $value = array_merge_recursive(['options' => ['autofocus' => true]], $value);
        $this->attributes[$key] = $value;
    }
}
