<?php

namespace kozlovsv\crud\widgets;

use kartik\builder\Form;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;


/**
 * Верхняя панель с кнопками "Добавить" и формой фильтров страницы Index
 */
class SearchPanel extends Widget
{
    /**
     * Модель для формы поиска
     *
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * Массив настроек полей для формы поиска (для построителя форм  kartik\builder\Form)
     * @see \kartik\builder\BaseForm::attributes
     * @var array
     */
    public $attributes = [];


    public function run()
    {
        if (!empty($this->attributes)) {
            $form = ActiveFormSearch::begin();
            Form::widget(
                [
                    'model' => $this->model,
                    'form' => $form,
                    'attributes' => $this->attributes,
                ]
            );

            echo Html::tag('div', Html::submitButton(Html::icon('search'), ['class' => 'btn btn-default']), ['class' => 'form-group']);
            $resetButton = FilterReset::widget(['model' => $this->model, 'url' => ['index'],]);
            if ($resetButton) echo Html::tag('div', $resetButton, ['class' => 'form-group']);
            ActiveFormSearch::end();
        }
    }
}
