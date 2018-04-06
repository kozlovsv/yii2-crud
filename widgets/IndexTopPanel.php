<?php

namespace kozlovsv\crud\widgets;

use kozlovsv\crud\helpers\ModelPermission;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;


/**
 * Верхняя панель с кнопками "Добавить" и формой фильтров страницы Index
 */
class IndexTopPanel extends Widget
{
    /**
     * Модель для формы поиска
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * Массив настроек полей для формы поиска (для виджета CrudField)
     * [
     *      'defaultValue' => 1 //Значение по умолчанию
     *      'attribute' => 'name' //Наименование атрибута модели
     *      'format' => 'textInput' //Формат поля (текст, календерь). Вызывается в форме ActiveField->$format(...);
     *      'options' => ['class' => 'myClass'] //Массив HTML опций поля
     * ]
     * @var array
     */
    public $searchFields = [];

    /**
     * Флаг, который указывает отрабатывать ли нажатие кнопок в панели в диалоговом режиме или обычном.
     * @var bool
     */
    public $isModal = true;

    /**
     * @var array Массиы кнопок с напаметрами для создания.
     * Параметра как для функции Html::a
     * [
     *   text => '',
     *   url => [],
     *   options = [],
     * ]
     *
     */
    public $buttons = [];

    /**
     * Имя класса CrudField
     * @var string
    */
    public $crudFieldClass = CrudField::class;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->initDefaultButtons();
    }

    public function run()
    {
        echo Html::beginTag('div', ['class' => 'pull-left']);
        $this->renderButtonsPanel();
        $this->renderFilters();

        echo Html::endTag('div');
    }

    protected function renderButtonsPanel()
    {
        if (ModelPermission::canCreate($this->model->tableName())) {
            echo Html::beginTag('div', ['class' => 'btn-group pull-left']);
            $this->renderButtons();
            echo Html::endTag('div');
        };
    }

    protected function renderFilters()
    {
        if (!empty($this->searchFields)) {
            echo Html::beginTag('div', ['class' => 'pull-left']);
            $form = SearchActiveForm::begin();
            foreach ($this->searchFields as $params) {
                /** @noinspection PhpUndefinedMethodInspection */
                echo $this->crudFieldClass::widget([
                    'model' => $this->model,
                    'form' => $form,
                    'params' => $params,
                ]);
            }
            echo Html::submitButton(Html::icon('search'), ['class' => 'btn btn-default']);
            FilterReset::widget(['model' => $this->model, 'url' => ['index'],]);
            SearchActiveForm::end();
            echo Html::endTag('div');
        }
    }

    public static function defaultButtons($isModal) {
        return [
          [
              'text' => 'Добавить',
              'url' => ['create'],
              'options' => ['class' => 'btn btn-success btn-create', 'data-modal' => $isModal ? 1 : 0, 'data-pjax' => 0],
          ]
        ];
    }

    protected function initDefaultButtons()
    {
        if (empty($this->buttons))
            $this->buttons = self::defaultButtons($this->isModal);
    }

    protected function renderButtons()
    {
        foreach ($this->buttons as $button) {
            echo Html::a($button['text'], $button['url'], $button['options']);
        }
    }
}
