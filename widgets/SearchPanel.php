<?php

namespace kozlovsv\crud\widgets;

use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;
use yii\db\ActiveRecord;


/**
 * Верхняя панель с кнопками "Добавить" и формой фильтров страницы Index
 */
class SearchPanel extends Widget
{
    /**
     * Модель для формы поиска
     *
     * @var ActiveRecord
     */
    public $model;

    /**
     * Массив настроек полей для формы поиска (для построителя форм  kartik\builder\Form)
     * @see \kartik\builder\BaseForm::attributes
     * @var array
     */
    public $attributes = [];

    /**
     * URL при нажатии кнопки RESET по умолчанию текущий URL без параметров controller->getRoute()
     * @var array
     */
    public $resetUrl = [];

    /**
     * конфиг для формы поиска.
     * @var array
     * @see ActiveFormSearch
     */
    public $formSearchConfig = [];


    public function run()
    {
        if (!empty($this->attributes)) {
            $form = ActiveFormSearch::begin($this->formSearchConfig);
            echo FormBuilder::widget(
                [
                    'model' => $this->model,
                    'form' => $form,
                    'attributes' => $this->attributes,
                    'needAutoFocus' => false,
                    'options' => [
                        'tag' => 'div',
                        'class' => 'form-group',
                    ],
                ]
            );

            $resetButton = FilterReset::widget(['model' => $this->model, 'url' => $this->resetUrl ? $this->resetUrl : [Yii::$app->controller->getRoute()],]);
            if ($resetButton) echo Html::tag('div', $resetButton, ['class' => 'form-group']);
            ActiveFormSearch::end();
        }
    }
}