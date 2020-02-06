<?php
namespace kozlovsv\crud\widgets;

use kozlovsv\crud\helpers\ModelPermission;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{

    /** Размер страницы по умолчанию */
    const DEFAULT_PAGE_SIZE = 100;

    /**
     * @var array
     */
    public $actionColumnsBefore;

    /**
     * @var array
     */
    public $actionColumnsAfter;

    /**
     * Флаг, который указывает отрабатывать ли нажатие кнопок добавления редактирования и удаления как модальные или нет.
     * @var bool
     */
    public $isModal = true;

    /**
     * Название раздела для проверки разрешений.
     * @var string
     */
    public $permissionCategory;


    /**
     * Опции заголовков таблицы
     * @var array
     */
    public $headerRowOptions = ['class' => 'sort-header'];

    public $layout = "{items}\n<div class='navbar-fixed-bottom'><div class='container'>{summary}\n{pager}</div></div>";

    public $containerOptions = ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12', 'style' => 'margin-bottom: 50px'];

    /**
     * @init
     */
    public function init()
    {
        if ($this->dataProvider instanceof ActiveDataProvider && $this->dataProvider->pagination) {
            $this->dataProvider->pagination->pageSize = self::DEFAULT_PAGE_SIZE;
        }
        $this->initDefaultActionColumnsBefore();
        $this->initDefaultActionColumnsAfter();
        parent::init();
    }

    public function renderContainerBegin(){
        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', $this->containerOptions);
        Pjax::begin([
            'id' => 'pjax-table',
            'formSelector' => false,
            'scrollTo' => 1
        ]);
    }

    public function renderContainerEnd(){
        Pjax::end();
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

    public static function defaultActionColumnsBefore($isModal, $permissionCategory){
        return [
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'isModal' => $isModal,
                'visible' => ModelPermission::canView($permissionCategory),
            ],
        ];
    }

    public static function defaultActionColumnsAfter($isModal, $permissionCategory){
        return [
            [
                'class' => ActionColumn::class,
                'template' => '{update}',
                'isModal' => $isModal,
                'visible' => ModelPermission::canUpdate($permissionCategory),
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
                'visible' => ModelPermission::canDelete($permissionCategory),
            ],
        ];
    }

    protected function initDefaultActionColumnsBefore()
    {
        if ($this->actionColumnsBefore === null) {
            $this->actionColumnsBefore = self::defaultActionColumnsBefore($this->isModal, $this->permissionCategory);
        }
    }

    protected function initDefaultActionColumnsAfter()
    {
        if ($this->actionColumnsAfter === null) {
            $this->actionColumnsAfter = self::defaultActionColumnsAfter($this->isModal, $this->permissionCategory);
        }
    }

    protected function initColumns()
    {
        $this->columns = array_merge($this->actionColumnsBefore, $this->columns, $this->actionColumnsAfter);
        parent::initColumns();
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        $this->renderContainerBegin();
        parent::run();
        $this->renderContainerEnd();
    }
}
