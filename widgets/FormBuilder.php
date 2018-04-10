<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 05.10.2017
 * Time: 19:15
 */

namespace kozlovsv\crud\widgets;


use kartik\builder\Form;

/**
 * Расширение построителя форм kartik\builder\Form
 */
class FormBuilder extends Form
{
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
                $newAttributes[$setting] = ['type' => self::INPUT_TEXT];
            } else {
                $newAttributes[$key] = $setting;
            }
        }
        $attributes = $newAttributes;
        parent::prepareAttributes($attributes);
    }
}