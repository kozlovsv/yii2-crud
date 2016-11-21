<?php

namespace kozlovsv\crud\widgets;

use yii\bootstrap\Html;
use kozlovsv\helpers\ModelPermission;
use yii\base\Widget;
use yii\widgets\Pjax;

/**
 * Class ActiveForm
 */
class CrudIndexEmpty extends Widget
{
    /**
     * Модель для формы поиска
     * @var \yii\db\ActiveRecord
     */
    public $searchModel;

    /**
     * Флаг работать в диалоговом режиме или в стандартном.
     * @var bool
     */
    public $isModal = true;

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
     * @inheritdoc
     */
    public function init() {
        $this->renderBeginForm();
        $this->renderTopPanel();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->renderTable();
        $this->renderBottomPanel();
        $this->renderEndForm();
    }

    /**
     * Отрисовка начала формы
     */
    protected function renderBeginForm()
    {
        Pjax::begin([
            'id' => 'pjax-content',
            'formSelector' => false,
        ]);
    }

    /**
     * Отрисовка верхней панели (кнопка добавить, фильтры)
     */
    protected function renderTopPanel()
    {
        echo Html::beginTag('div', ['class' => 'pull-left']);
        if (ModelPermission::canCreate($this->searchModel->tableName())) {
            echo Html::beginTag('div', ['class' => 'pull-left']);
            echo Html::a('Добавить', ['create'], ['class' => 'btn btn-success btn-create', 'data-modal' => $this->isModal ? 1 : 0, 'data-pjax' => 0]);
            echo Html::endTag('div');
        };
        //Панель фильтров
        if (!empty($this->searchFields)) {
            echo Html::beginTag('div', ['class' => 'pull-left']);
            echo $this->render('_search', ['model' => $this->searchModel, 'fields' => $this->searchFields]);
            echo Html::endTag('div');
        }
        echo Html::endTag('div');
    }

    /**
     * Отрисовка блока таблицы
     */
    protected function renderTable()
    {
        //empty
    }

    /**
     * Отрисовка нижней панели
     */
    protected function renderBottomPanel()
    {
        //empty
    }

    /**
     * Отрисовка закрывающих тегов формы
     */
    private function renderEndForm()
    {
        Pjax::end();
    }

}