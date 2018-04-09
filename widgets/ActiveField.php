<?php

namespace kozlovsv\crud\widgets;

use kartik\select2\Select2;
use kozlovsv\crud\helpers\Html;
use kozlovsv\datepicker\DateTimePicker;
use yii\helpers\ArrayHelper;

class ActiveField extends \yii\bootstrap\ActiveField
{
     /**
     * Массив свойств для input-group-addon поля imput в Bootstrap3
     * @var array
     */
    public $addon;
    
    /**
     * Макет поля checkbox
     * @var string
     */
    public $checkboxTemplate = "<div class=\"checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{hint}\n</div>";

    /**
     * @var \yii\base\Model | \yii\db\ActiveRecord the data model that this field is associated with
     */
    public $model;

    /**
     * Прирендерить поле
     * @param null $content
     * @return string
     */
    public function render($content = null)
    {
        $this->template = strtr($this->template, [
            '{input}' => $this->generateAddon()
        ]);
        return parent::render($content);
    }

    /**
     * Календарь
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function datePicker($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        Html::addCssClass($this->inputOptions, 'filter-field-date');
        $this->parts['{input}'] = DateTimePicker::widget([
                'model' => $this->model,
                'attribute' => $this->attribute,
                'options' => $options,
                'clientOptions' => [
                    'language' => 'ru',
                    'format' => 'DD.MM.YYYY',
                    'useCurrent' => false,
                    'pickTime' => false,
                ],
            ]);
        return $this;
    }

    /**
     * Select2
     * @param array $items
     * @param array $options
     * @param array $pluginOptions
     * @return $this
     * @throws \Exception
     */
    public function select2($items, $options = [], $pluginOptions = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Select2::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'data' => $items,
            'options' => $options,
            'pluginOptions' => array_merge(['allowClear' => true], $pluginOptions),
        ]);
        return $this;
    }

    /**
     * Установить значение по умолчанию
     * @param $value
     * @return ActiveField
     */
    public function setDefaultValue($value)
    {
        if ($this->model->isNewRecord) {
            $this->model->{$this->attribute} = $value;
        }

        return $this;
    }

    /**
     * Календарь со временем
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function dateTimePicker($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        Html::addCssClass($this->inputOptions, 'filter-field-datetime');
        $this->parts['{input}'] = DateTimePicker::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'options' => $options,
            'clientOptions' => [
                'language' => 'ru',
                'format' => 'DD.MM.YYYY HH:mm',
            ],
        ]);
        return $this;
    }

    /**
     * Загрузка файла
     * @param array $options
     * @param string | null $label
     * @return $this
     */
    public function fileInput($options = [], $label = null)
    {
        if ($this->inputOptions !== ['class' => 'form-control']) {
            $options = array_merge($this->inputOptions, $options);
        }
        $this->adjustLabelFor($options);

        $options = array_merge($options, ['class' => 'upload']);
        $this->parts['{input}'] = '<div class="file-block">
            <div class="file-upload">
                ' . $label . '
                ' . Html::activeFileInput($this->model, $this->attribute, $options) . '
            </div>
        </div>
    ';
        return $this;
    }

    /**
     * Сгенерировать аддон
     * @return string
     */
    protected function generateAddon()
    {
        if (empty($this->addon)) {
            return '{input}';
        }
        $addon = $this->addon;
        $prepend = static::getAddonContent(ArrayHelper::getValue($addon, 'prepend', ''));
        $append = static::getAddonContent(ArrayHelper::getValue($addon, 'append', ''));
        $content = $prepend . '{input}' . $append;
        $group = ArrayHelper::getValue($addon, 'groupOptions', []);
        Html::addCssClass($group, 'input-group');
        $contentBefore = ArrayHelper::getValue($addon, 'contentBefore', '');
        $contentAfter = ArrayHelper::getValue($addon, 'contentAfter', '');
        $content = Html::tag('div', $contentBefore . $content . $contentAfter, $group);
        return $content;
    }

    /**
     * Прикрепить аддон
     * @param $addon
     * @return string
     */
    public static function getAddonContent($addon)
    {
        if (!is_array($addon)) {
            return $addon;
        }
        $content = ArrayHelper::getValue($addon, 'content', '');
        $options = ArrayHelper::getValue($addon, 'options', []);
        if (ArrayHelper::getValue($addon, 'asButton', false) == true) {
            Html::addCssClass($options, 'input-group-btn');
            return Html::tag('span', $content, $options);
        } else {
            Html::addCssClass($options, 'input-group-addon');
            return Html::tag('span', $content, $options);
        }
    }
}