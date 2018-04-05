<?php

namespace kozlovsv\crud\widgets;

use Closure;
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
     * [
     *      'defaultValue' => 1 //Значение по умолчанию
     *      'attribute' => 'name' //Наименование атрибута модели
     *      'format' => 'textInput' //Формат поля (текст, календерь). Вызывается в форме ActiveField->$format(...);
     *      'options' => ['class' => 'myClass'] //Массив HTML опций поля
     *      'items' => [] //Массив списков для выпадающих списков
     *      'fieldOptions' => [] //Массив $options в функции ActiveField->field
     * ]
     * Если элемент массива $value - строка, то это интерпретируется как текстовое поле с атрибутом $value
     * @var array|string
     */
    public $params;

    /**
     * Указывает является ли поле первым в форме, это нужно для установки автофокуса на это поле.
     * @var bool
     */
    public $first = false;

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
        $this->normalizeParams();
        $attribute = $this->normalizeAttribute();
        $format = $this->normalizeFormat();
        $options = $this->normalizeOptions($format);
        $items = $this->normalizeItems();
        $fieldOptions = $this->normalizeFieldOptions();

        return $this->renderField($attribute, $format, $options, $items, $fieldOptions);
    }

    /**
     * @param $attribute
     * @param $format
     * @param $options
     * @param $items
     * @param $fieldOptions
     * @return ActiveField the created ActiveField object
     */
    public function renderField($attribute, $format, $options, $items, $fieldOptions)
    {
        /** @var ActiveField $field */
        $field = $this->form->field($this->model, $attribute, $fieldOptions);
        if (isset($this->params['defaultValue'])) {
            $field->setDefaultValue($this->params['defaultValue']);
        }
        if (!($format instanceof Closure)) {
            if ($items !== null) {
                $field->$format($items, $options);
            } else {
                $field->$format($options);
            }
        } else {
            call_user_func($format, $field, $this);
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
        if ($this->first && !isset($opt['autofocus'])) $opt['autofocus'] = true;
        if ($format == 'textInput' && !isset($opt['maxlength'])) $opt['maxlength'] = true;
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

    /**
     * Нормализовать параметр options для функции ->field
     * @return array|mixed
     */
    protected function normalizeFieldOptions()
    {
        return isset($this->params['fieldOptions']) ? $this->params['fieldOptions'] : [];
    }

    /**
     * Нормализация поля Params. Если поле текстовое, то тогда надо развернуть его
     */
    protected function normalizeParams()
    {
        if (!empty($this->params) && is_string($this->params)) {
            $arr = explode(':', $this->params);
            $this->params = [];
            $this->params['attribute'] = $arr[0];
            if (!empty($arr[1])) $this->params['format'] = $arr[1];
        }
    }
}
