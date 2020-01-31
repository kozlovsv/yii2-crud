<?php

namespace kozlovsv\crud\widgets;

use ReflectionClass;
use ReflectionException;
use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;
use yii\db\ActiveRecord;

/**
 * Кнопка сброса фильтра
 *
 * @author Ilya Norkin <ilya@itender.kz>
 */
class FilterReset extends Widget
{
    /**
     * Линк
     * @var string
     */
    public $url;

    /**
     * Заголовок
     * @var string
     */
    public $label = '<span class="glyphicon glyphicon-remove"></span>';

    /**
     * Модель
     * @var ActiveRecord
     */
    public $model;

    /**
     * Опции кнопки
     * @var array
     */
    public $options = ['class' => 'btn btn-default', 'style' => 'color: #a52a2a'];

    /**
     * @return string
     */
    public function run()
    {
        parent::run();
        if ($this->isFilter()) {
            return Html::a($this->label, $this->url, $this->options);
        }
        return '';
    }

    /**
     * Отфильтровано ли?
     * @return bool
     */
    protected function isFilter()
    {
        $attributes = $this->model->safeAttributes();
        foreach ($attributes as $attribute) {
            if ($this->model->$attribute !== null) {
                return true;
            }
        }
        return false;
    }

    /**
     * Получить параметры
     * @return array
     * @throws ReflectionException
     */
    protected function getParams()
    {
        $modelClassName =  (new ReflectionClass($this->model))->getShortName();
        return isset(Yii::$app->request->queryParams[$modelClassName]) ? Yii::$app->request->queryParams[$modelClassName] : [];
    }
}