<?php

namespace kozlovsv\crud\widgets;

use kartik\form\ActiveField;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * Поле для формы поиска (филтры)
 * Class ActiveSearchField
 * @package kozlovsv\crud\widgets
 */
class ActiveFieldSearch extends ActiveField
{
    /**
     * Инициализация
     */
    public function init()
    {
        parent::init();
        $this->renderActiveClass();
        $this->attachDefaultLabel();
    }

    /**
     * @inheritdoc
     */
    protected function renderActiveClass()
    {
        $containerClass = 'search-field';
        if ($this->model->{$this->attribute} != null) {
            $containerClass .= ' active';
        }
        Html::addCssClass($this->options, $containerClass);
    }

    /**
     * Установить дефолтное название поля
     */
    protected function attachDefaultLabel()
    {
        $this->inputOptions = array_merge($this->getDefaultLabel(), $this->inputOptions);
    }

    /**
     * Получить дефолтное название поля
     */
    protected function getDefaultLabel()
    {
        $label = $this->model->getAttributeLabel($this->attribute);
        $defaultOptions = ['placeholder' => $label, 'prompt' => $label];
        return $defaultOptions;
    }

    public function widget($class, $config = [])
    {
        if (is_subclass_of($class, 'yii\widgets\InputWidget')) {
            if (!isset($config['options'])) $config['options'] = [];
            $config['options'] = ArrayHelper::merge($this->getDefaultLabel(), $config['options']);
        }
        return parent::widget($class, $config);
    }
}
