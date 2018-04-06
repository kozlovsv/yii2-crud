<?php

namespace kozlovsv\crud\widgets;

use yii\bootstrap\Html;

/**
 * Поле для формы поиска (филтры)
 * Class ActiveSearchField
 * @package kozlovsv\crud\widgets
 */
class SearchActiveField extends ActiveField
{

    /**
     * @var \yii\base\Model | \yii\db\ActiveRecord the data model that this field is associated with
     */
    public $model;

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
     * Дефолтное название поля
     */
    protected function attachDefaultLabel()
    {
        $label = $this->model->getAttributeLabel($this->attribute);
        $defaultOptions = ['placeholder' => $label, 'prompt' => $label];
        $this->inputOptions = array_merge($defaultOptions, $this->inputOptions);
    }

}