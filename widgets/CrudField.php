<?php

namespace kozlovsv\crud\widgets;

use kozlovsv\widgets\ActiveField;
use yii\base\Widget;

class CrudField extends Widget
{
    /**
     * Модель
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * Форма
     * @var \kozlovsv\widgets\ActiveForm
     */
    public $form;

    /**
     * Параметры
     * @var array
     */
    public $params;

    /**
     * Формат поля по умолчанию
     * @var string
     */
    protected $defaultFormat = 'textInput';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $attribute = $this->normalizeAttribute();
        $format = $this->normalizeFormat();
        $options = $this->normalizeOptions($format);
        $items = $this->normalizeItems();

        return $this->renderField($attribute, $format, $options, $items);
    }

    /**
     * @param $attribute
     * @param $format
     * @param $options
     * @param $items
     * @return ActiveField the created ActiveField object
     */
    public function renderField($attribute, $format, $options, $items)
    {

        /** @var ActiveField $field */
        $field = $this->form->field($this->model, $attribute);
        if (isset($this->params['defaultValue'])) {
            $field->setDefaultValue($this->params['defaultValue']);
        }
        if ($items !== null) {
            $field->$format($items, $options);
        } else {
            $field->$format($options);
        }

        return $field;
    }

    /**
     * Нормализовать аттрибут
     * @return array|mixed
     */
    protected function normalizeAttribute()
    {
        return isset($this->params['attribute']) ? $this->params['attribute'] : $this->params;
    }

    /**
     * Нормализовать формат
     * @return mixed|string
     */
    protected function normalizeFormat()
    {
        return isset($this->params['format']) ? $this->params['format'] : $this->defaultFormat;
    }

    /**
     * Нормализовать опции
     * @param string $format
     * @return array
     */
    protected function normalizeOptions($format)
    {
        $opt = isset($this->params['options']) ? $this->params['options'] : [];
        if ($format == 'textInput') {
            $opt = array_merge(['maxlength' => true], $opt);
        }
        return $opt;
    }

    /**
     * Нормализовать список значений
     * @return mixed|null
     */
    protected function normalizeItems()
    {
        return isset($this->params['items']) ? $this->params['items'] : null;
    }

}